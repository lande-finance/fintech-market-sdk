<?php

namespace Hashstudio\FintechMarketSdk;

use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class FintechMarketSdk
{
    const BASE_URL = 'https://decision.fintech-market.com/api/v1/';
    const OAUTH_URL = 'https://entrance.fintech-market.com/oauth/token';

    public function __construct(
        readonly string $clientId,
        readonly string $clientSecret,
        readonly string $organization,
        readonly bool $debug = false
    ) {
    }

    protected function httpClient(): PendingRequest
    {
        $debugMiddleware = Middleware::mapRequest(function (RequestInterface $request) {
            Log::debug('[Fintech market debug] Request: ' . Message::toString($request) . '\n\n');
            return $request;
        });
        $responseMiddleware = Middleware::mapResponse(function (ResponseInterface $response) {
            Log::debug('[Fintech market debug] Request: ' . Message::toString($response) . '\n\n');
            return $response;
        });

        $request = Http::withOptions(['base_uri' => self::BASE_URL , 'debug' => $this->debug]);

        if ($this->debug) {
            $request->withMiddleware($debugMiddleware);
            $request->withMiddleware($responseMiddleware);
        }

        return $request;
    }

    protected function oauthHttpClient(): PendingRequest
    {
        return Http::withOptions(['base_uri' => self::OAUTH_URL, 'debug' => true]);
    }

    public function getAccessToken(): string
    {
        // Check if the access token is cached, if not, request a new one and cache it
        $accessToken = cache()->get('fintech_market_api_access_token');

        if (!$accessToken) {
            $response = $this->oauthHttpClient()->withHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret),
                'Content-Type' => 'application/x-www-form-urlencoded',
                'x-organization' => $this->organization,
            ])->asForm()->post('', [
                'grant_type' => 'client_credentials',
            ]);

            $response->throw();

            $accessToken = $response->json('access_token');

            // Cache the access token for the duration of its validity
            cache()->put('fintech_market_api_access_token', $accessToken, $response->json('expires_in'));
        }

        return $accessToken;
    }

    public function pushInquiry(string $branch, string $scenario, array $payload): array
    {
        $response = $this->httpClient()->withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
            'x-organization' => $this->organization,
        ])->post($branch . '/scenarios/' . $scenario . '/inquiries', $payload); // Add '/inquiries' to the endpoint


        $response->throw();

        return $response->json();
    }
}
