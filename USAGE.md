# Usage

## Installation

```bash
composer require omisai/php-creditonline
```

## Configuration

The `Configuration` class controls the API client behaviour. By default, it connects to the production API.

```php
use Omisai\CreditOnline\Configuration;

$config = new Configuration();

// Use the test environment
$config->setHost('https://api-test.creditonline.hu/v3');

// Enable debug mode (logs to stdout)
$config->setDebug(true);

// Set a custom user agent
$config->setUserAgent('MyApp/1.0');

// mTLS authentication
$config->setCertFile('/path/to/cert.pem');
$config->setKeyFile('/path/to/key.pem');
```

### Host selection via index

Each API class constructor accepts a `$hostIndex` (default `0` = production). Use `1` for the test environment:

```php
$api = new AdatokLekrseAzonostAlapjnApi(config: $config, hostIndex: 1);
```

Or set it after construction:

```php
$api->setHostIndex(1);
```

### Custom Guzzle client

You can inject a custom `GuzzleHttp\ClientInterface` for custom HTTP behaviour (proxies, retry middleware, etc.):

```php
$client = new \GuzzleHttp\Client(['timeout' => 30]);
$api = new TokenGenerlsApi(client: $client);
```

## API Endpoints

### 1. Token Generation — `TokenGenerlsApi`

Generates a session token required by all other endpoints. The token is valid for 24 hours and is returned in the response headers.

```php
use Omisai\CreditOnline\Api\TokenGenerlsApi;

$api = new TokenGenerlsApi();

// Simple call (returns void — token is set server-side)
$api->tokenGet('your-api-key');

// To retrieve the token, use the WithHttpInfo variant:
list($body, $statusCode, $headers) = $api->tokenGetWithHttpInfo('your-api-key');

// Optionally specify format and language:
// $api->tokenGet('your-api-key', 'json', 'hu');
```

| Parameter | Type | Required | Default | Description |
|-----------|------|----------|---------|-------------|
| `$api_key` | `string` | Yes | — | Subscriber API key |
| `$format` | `string` | No | `'json'` | Response format |
| `$language` | `string` | No | `'hu'` | Data language |

**Return:** `void`

---

### 2. Company Data — `AdatokLekrseAzonostAlapjnApi`

Retrieves company data by registration number or tax number.

```php
use Omisai\CreditOnline\Api\AdatokLekrseAzonostAlapjnApi;
use Omisai\CreditOnline\Model\ApiResult;

$api = new AdatokLekrseAzonostAlapjnApi();

// By registration number
$result = $api->dataGet($token, regnumber: '01-09-562111');

// By tax number (use "EV_" prefix for sole proprietors)
$result = $api->dataGet($token, taxnumber: '12345678-2-41');

// $result is an ApiResult object
var_dump($result->getLimitReached()); // bool
foreach ($result->getCompanies() as $company) {
    echo $company->getName();
    echo $company->getTaxnumber();
    echo $company->getRating();
}
```

| Parameter | Type | Required | Default | Description |
|-----------|------|----------|---------|-------------|
| `$token` | `string` | Yes | — | Session token |
| `$regnumber` | `string` | No | `null` | Registration number (`"EV_"` prefix for sole proprietors) |
| `$taxnumber` | `string` | No | `null` | Tax number (`"EV_"` prefix for sole proprietors) |

At least one of `$regnumber` or `$taxnumber` must be provided.

**Return:** `Omisai\CreditOnline\Model\ApiResult`

---

### 3. Daily Monitoring — `NapiMonitoringLekrdezseApi`

Fetches monitoring events (changes) for a given date.

```php
use Omisai\CreditOnline\Api\NapiMonitoringLekrdezseApi;

$api = new NapiMonitoringLekrdezseApi();

$events = $api->dailyMonitoringGet($token, new \DateTime('2025-01-15'));

foreach ($events as $event) {
    echo $event->getName();       // Company name
    echo $event->getTaxnumber();  // Tax number
    echo $event->getCategory();   // Event category
    echo $event->getLink();       // Detail link
}
```

| Parameter | Type | Required | Default | Description |
|-----------|------|----------|---------|-------------|
| `$token` | `string` | Yes | — | Session token |
| `$date` | `\DateTime` | Yes | — | Date to query |

**Return:** `Omisai\CreditOnline\Model\Event[]`

---

### 4. Subscriber Profile — `ProfilSForgalmiAdatokLekrdezseApi`

Retrieves subscriber profile information including usage quotas.

```php
use Omisai\CreditOnline\Api\ProfilSForgalmiAdatokLekrdezseApi;

$api = new ProfilSForgalmiAdatokLekrdezseApi();

$profile = $api->profileGet($token);

echo $profile->getCompanyName();
echo $profile->getActualFormat();
echo $profile->getActualLanguage();

foreach ($profile->getActualUsages() as $usage) {
    echo $usage->getType();   // Usage type
    echo $usage->getLimit();  // Limit
    echo $usage->getIds();    // IDs
}
```

| Parameter | Type | Required | Default | Description |
|-----------|------|----------|---------|-------------|
| `$token` | `string` | Yes | — | Session token |

**Return:** `Omisai\CreditOnline\Model\Profile`

## Models

| Model | Key Getters | Description |
|-------|-------------|-------------|
| `ApiResult` | `getLimitReached()`, `getCompanies()` | Top-level data query response |
| `Company` | `getRegnumber()`, `getTaxnumber()`, `getName()`, `getLongName()`, `getHeadquarter()`, `getStatus()`, `getFoundation()`, `getFunds()`, `getEmployees()`, `getLastTurnover()`, `getMainActivityCode()`, `getRating()`, `getCreditLimit()`, `getIndustry()`, `getType()`, `getKshNumber()`, `getEuTaxnumber()`, `getLink()`, `getBankAccounts()`, `getPhones()`, `getEmails()`, `getWebpages()`, `getNegativeInfo()`, `getPositiveInfo()`, `getFinancialSummaries()`, `getSigners()`, `getMembers()`, `getAuditors()`, `getSites()`, `getHasDeletedTaxNumber()`, `getHasActivePositiveInfo()`, `getHasActiveNegativeInfo()`, `getIsKoztartozasmentes()`, `getIsMegbizhatoAdozo()`, `getHasProhibitedMember()`, `getSignerChangeIn12Months()`, `getMemberChangeIn12Months()`, `getHeadquarterChangeIn12Months()` | Full company profile |
| `Address` | `getCountryCode()`, `getZip()`, `getCity()`, `getStreet()`, `getPlaceType()`, `getHouseNumber()` | Postal address |
| `Event` | `getTaxnumber()`, `getName()`, `getCategory()`, `getLink()` | Daily monitoring event |
| `Profile` | `getCompanyName()`, `getActualFormat()`, `getActualLanguage()`, `getActualUsages()` | Subscriber profile |
| `ActualUsage` | `getIds()`, `getLimit()`, `getType()` | Usage quota entry |
| `FinancialSummary` | — | Financial data for a fiscal year |
| `NegativeInfo` | `getType()`, `getCaseNumber()`, `getStart()`, `getEnd()` | Negative credit information |
| `PositiveInfo` | — | Positive credit information |
| `Signer` | — | Company signer/representative |
| `Member` | — | Company owner/member |
| `Auditor` | — | Company auditor |

All models implement `\ArrayAccess` and `\JsonSerializable`.

## Error Handling

All API methods throw `Omisai\CreditOnline\ApiException` on non-2xx responses or connection failures.

```php
use Omisai\CreditOnline\ApiException;

try {
    $result = $api->dataGet($token, regnumber: '01-09-562111');
} catch (ApiException $e) {
    echo 'HTTP Status: ' . $e->getCode();
    echo 'Message: ' . $e->getMessage();
    // $e->getResponseHeaders() returns response headers (nullable)
    // $e->getResponseBody() returns response body as string (nullable)
}
```

## Advanced

### Async methods

All endpoints provide async variants returning Guzzle promises:

```php
$promise = $api->dataGetAsync($token, regnumber: '01-09-562111');
$promise->then(function (ApiResult $result) {
    foreach ($result->getCompanies() as $company) {
        echo $company->getName();
    }
});
```

### `*WithHttpInfo()` methods

Each endpoint has a `*WithHttpInfo()` variant that returns an array of `[$response, $statusCode, $headers]` instead of just the response body:

```php
[$result, $statusCode, $headers] = $api->dataGetWithHttpInfo($token, regnumber: '01-09-562111');
```

Async `*WithHttpInfo()` variants are also available:

```php
$promise = $api->dataGetAsyncWithHttpInfo($token, regnumber: '01-09-562111');
```

## Full Example

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Omisai\CreditOnline\Api\TokenGenerlsApi;
use Omisai\CreditOnline\Api\AdatokLekrseAzonostAlapjnApi;
use Omisai\CreditOnline\Api\NapiMonitoringLekrdezseApi;
use Omisai\CreditOnline\Api\ProfilSForgalmiAdatokLekrdezseApi;
use Omisai\CreditOnline\Configuration;

$apiKey = 'your-api-key';

// Use test environment
$config = new Configuration();
$config->setHost('https://api-test.creditonline.hu/v3');

// 1. Generate token
$tokenApi = new TokenGenerlsApi($config);
list(, , $headers) = $tokenApi->tokenGetWithHttpInfo($apiKey);
$token = $headers['Token'][0] ?? null; // Token returned in response headers

// 2. Look up a company by registration number
$dataApi = new AdatokLekrseAzonostAlapjnApi($config);
$result = $dataApi->dataGet($token, regnumber: '01-09-562111');

echo 'Limit reached: ' . ($result->getLimitReached() ? 'Yes' : 'No') . "\n\n";

foreach ($result->getCompanies() as $company) {
    echo 'Company: ' . $company->getName() . "\n";
    echo 'Tax number: ' . $company->getTaxnumber() . "\n";
    echo 'Rating: ' . $company->getRating() . "\n";
    echo 'Credit limit: ' . $company->getCreditLimit() . "\n\n";
}

// 3. Fetch daily monitoring events
$monitoringApi = new NapiMonitoringLekrdezseApi($config);
$events = $monitoringApi->dailyMonitoringGet($token, new \DateTime('yesterday'));

foreach ($events as $event) {
    echo $event->getName() . ' — ' . $event->getCategory() . "\n";
}

// 4. Check profile usage
$profileApi = new ProfilSForgalmiAdatokLekrdezseApi($config);
$profile = $profileApi->profileGet($token);

echo 'Profile: ' . $profile->getCompanyName() . "\n";
foreach ($profile->getActualUsages() as $usage) {
    echo '  ' . $usage->getType() . ': ' . $usage->getIds() . ' / ' . $usage->getLimit() . "\n";
}
```
