# Omisai\CreditOnline\GetDataByIdService



All URIs are relative to https://api.creditonline.hu/v3, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**dataGet()**](GetDataByIdService.md#dataGet) | **GET** /Data |  |


## `dataGet()`

```php
dataGet($token, $regnumber, $taxnumber): \Omisai\CreditOnline\Model\ApiResult
```



A megadott azonosítók alapján adatcsomag letöltése. LimitReached = true esetén a forgalom elérte a beállított limitet

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new Omisai\CreditOnline\Api\GetDataByIdService(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$token = 'token_example'; // string | Egyedi token
$regnumber = 0109562111; // string | Cégjegyzékszám cég esetén. Egyéni vállalkozók esetén \"EV_\" előtaggal nyilvántartási szám.
$taxnumber = 12177705; // string | Adószám, egyéni vállalkozók esetén \"EV_\" előtaggal.

try {
    $result = $apiInstance->dataGet($token, $regnumber, $taxnumber);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling GetDataByIdService->dataGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **token** | **string**| Egyedi token | |
| **regnumber** | **string**| Cégjegyzékszám cég esetén. Egyéni vállalkozók esetén \&quot;EV_\&quot; előtaggal nyilvántartási szám. | [optional] |
| **taxnumber** | **string**| Adószám, egyéni vállalkozók esetén \&quot;EV_\&quot; előtaggal. | [optional] |

### Return type

[**\Omisai\CreditOnline\Model\ApiResult**](../Model/ApiResult.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/xml`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
