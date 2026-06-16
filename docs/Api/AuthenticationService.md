# Omisai\CreditOnline\AuthenticationService



All URIs are relative to https://api.creditonline.hu/v3, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getToken()**](AuthenticationService.md#getToken) | **GET** /Token |  |


## `getToken()`

```php
getToken($api_key, $format, $language)
```



Egyedi munkamenet kulcsot generál, ami a többi metódus hívásához szükséges. Egyszer kell lekérni, majd felhasználható a többi hívás során 24 órán keresztül. A kulcs generálásakor adható meg a lekérdezésekben kívánt formátum és nyelv, valamint az adattartalom.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new Omisai\CreditOnline\Api\AuthenticationService(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$api_key = 'api_key_example'; // string | Az előfizető egyedi API kulcsa
$format = 'json'; // string | A lekérések eredményének formátuma
$language = 'hu'; // string | Az adatok nyelve

try {
    $apiInstance->getToken($api_key, $format, $language);
} catch (Exception $e) {
    echo 'Exception when calling AuthenticationService->getToken: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **api_key** | **string**| Az előfizető egyedi API kulcsa | |
| **format** | **string**| A lekérések eredményének formátuma | [optional] [default to &#39;json&#39;] |
| **language** | **string**| Az adatok nyelve | [optional] [default to &#39;hu&#39;] |

### Return type

void (empty response body)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: Not defined

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
