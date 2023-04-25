<?php

namespace Hashstudio\FintechMarketSdk\Tests;

use Hashstudio\FintechMarketSdk\FintechMarketSdk;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FintechMarketSdkTest extends TestCase
{
    private FintechMarketSdk $fintechMarketSdk;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fintechMarketSdk = new FintechMarketSdk(
            'test_client_id',
            'test_client_secret',
            'test_organization',
            true
        );
    }

    public function testGetAccessToken(): void
    {
        Http::fake([
            FintechMarketSdk::OAUTH_URL => Http::response([
                'access_token' => 'test_access_token',
                'expires_in' => 3600,
            ], 200),
        ]);

        $accessToken = $this->fintechMarketSdk->getAccessToken();

        Http::assertSent(function (Request $request) {
            return $request->url() == FintechMarketSdk::OAUTH_URL &&
                $request->hasHeader('Authorization', 'Basic ' . base64_encode('test_client_id:test_client_secret')) &&
                $request->hasHeader('Content-Type', 'application/x-www-form-urlencoded') &&
                $request->hasHeader('x-organization', 'test_organization') &&
                $request['grant_type'] == 'client_credentials';
        });

        $this->assertSame('test_access_token', $accessToken);
    }

    public function testPushInquiry(): void
    {
        $branch = 'test_branch';
        $scenario = 'test_scenario';
        $payload = ['key' => 'value'];

        Http::fake([
            FintechMarketSdk::OAUTH_URL => Http::response([
                'access_token' => 'test_access_token',
                'expires_in' => 3600,
            ], 200),
            FintechMarketSdk::BASE_URL . $branch . '/scenarios/' . $scenario . '/inquiries' => Http::response([
                'result' => 'success',
            ], 200),
        ]);

        $response = $this->fintechMarketSdk->pushInquiry($branch, $scenario, $payload);

        Http::assertSent(function (Request $request) use ($branch, $scenario, $payload) {
            return $request->url() == FintechMarketSdk::BASE_URL . $branch . '/scenarios/' . $scenario . '/inquiries' &&
                $request->hasHeader('Authorization', 'Bearer test_access_token') &&
                $request->hasHeader('x-organization', 'test_organization') &&
                $request->data() == $payload;
        });

        $this->assertSame(['result' => 'success'], $response);
    }


}
