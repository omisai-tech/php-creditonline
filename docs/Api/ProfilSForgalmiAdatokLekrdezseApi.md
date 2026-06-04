# Omisai\CreditOnline\ProfilSForgalmiAdatokLekrdezseApi



All URIs are relative to https://api.creditonline.hu/v3, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**profileGet()**](ProfilSForgalmiAdatokLekrdezseApi.md#profileGet) | **GET** /Profile |  |


## `profileGet()`

```php
profileGet($token): \Omisai\CreditOnline\Model\Profile
```



Az aktuális tokenhez tartozó beállítások, profil és forgalmi adatok lekérdezése.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new Omisai\CreditOnline\Api\ProfilSForgalmiAdatokLekrdezseApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$token = 'token_example'; // string | Egyedi token

try {
    $result = $apiInstance->profileGet($token);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProfilSForgalmiAdatokLekrdezseApi->profileGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **token** | **string**| Egyedi token | |

### Return type

[**\Omisai\CreditOnline\Model\Profile**](../Model/Profile.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/xml`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
