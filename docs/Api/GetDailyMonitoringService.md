# Omisai\CreditOnline\GetDailyMonitoringService



All URIs are relative to https://api.creditonline.hu/v3, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getDailyMonitoring()**](GetDailyMonitoringService.md#getDailyMonitoring) | **GET** /DailyMonitoring |  |


## `getDailyMonitoring()`

```php
getDailyMonitoring($token, $date): \Omisai\CreditOnline\Model\Event[]
```



Az adott napi monitoring értesítőben foglalt adatváltozások kérhetőek le struktúrált, cégenként csoportosított formátumban.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new Omisai\CreditOnline\Api\GetDailyMonitoringService(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$token = 'token_example'; // string | Egyedi token
$date = new \DateTime('2013-10-20T19:20:30+01:00'); // \DateTime | Lekért értesítők dátuma

try {
    $result = $apiInstance->getDailyMonitoring($token, $date);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling GetDailyMonitoringService->getDailyMonitoring: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **token** | **string**| Egyedi token | |
| **date** | **\DateTime**| Lekért értesítők dátuma | |

### Return type

[**\Omisai\CreditOnline\Model\Event[]**](../Model/Event.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/xml`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
