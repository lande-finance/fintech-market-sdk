# Very short description of the package

fintech-market.com Laravel API SDK


## Installation

You can install the package via composer:

```bash
composer require hashstudio/fintech-market-sdk
```

## Usage

```php
php artisan vendor:publish --tag=fintech-market-config
```

```php
use Hashstudio\FintechMarketSdk\FintechMarketSdk;

$sdk = app(FintechMarketSdk::class);

$data = [
    'data' => [
        'person_external_id' => 'f3529593-e832',
        'entity_external_id' => 'f3529593-e832-40e9-bf1d-45272d8ffffsdfsd',
        'lock_version' => 5,
        'fields' => [
            'aml_factor' => 'true',
            'collateral_insurance' => '1',
            'collateral_types' => '1',
            'collateral_value_volatility' => '1',
            'data_reliability' => '1',
            'enforceability_of_collateral' => '1',
            'feasibility_of_collateral' => '1',
            'fin_borrowings' => '0',
            'fin_collateral_value' => '0',
            'fin_credit_history' => 'true',
            'fin_current_assets' => '1000',
            'fin_current_liabilities' => '1000',
            'fin_loan_amount' => '0',
            'fin_personal_income' => '9000',
            'fin_profit' => '5000',
            'fin_revenue' => '1000',
            'fin_total_assets' => '1000',
            'fin_total_liabilities' => '5000',
            'non_fin_experience' => '13',
            'non_fin_term_length' => '11',
            'reputation' => '1',
            'sustainability' => '1',
            'ultimate_beneficiary' => '1',
        ],
    ],
];

$branch = 'some_branch';
$scenarioKey = 'short_test';

$sdk->pushInquiry($branch, $scenarioKey, $data);
```


### Testing

```bash
composer test
```
