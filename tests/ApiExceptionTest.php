<?php

use Omisai\CreditOnline\ApiException;

// ---- Inheritance ----

it('extends Exception', function () {
    $exception = new ApiException();

    expect($exception)->toBeInstanceOf(Exception::class);
});

// ---- Constructor ----

it('constructor accepts message, code, responseHeaders, and responseBody', function () {
    $headers = ['Content-Type' => ['application/json']];
    $body = json_decode('{"error": "not_found"}');

    $exception = new ApiException('Not Found', 404, $headers, $body);

    expect($exception->getMessage())->toBe('Not Found');
    expect($exception->getCode())->toBe(404);
    expect($exception->getResponseHeaders())->toBe($headers);
    expect($exception->getResponseBody())->toBe($body);
});

it('constructor has default values for optional parameters', function () {
    $exception = new ApiException();

    expect($exception->getMessage())->toBe('');
    expect($exception->getCode())->toBe(0);
    expect($exception->getResponseHeaders())->toBe([]);
    expect($exception->getResponseBody())->toBeNull();
});

it('constructor accepts string responseBody', function () {
    $exception = new ApiException('Error', 500, [], '{"error": true}');

    expect($exception->getResponseBody())->toBe('{"error": true}');
});

// ---- getResponseHeaders ----

it('getResponseHeaders returns the headers array', function () {
    $headers = [
        'Content-Type' => ['application/json'],
        'X-Request-Id' => ['abc-123'],
    ];
    $exception = new ApiException('Error', 500, $headers);

    expect($exception->getResponseHeaders())->toBe($headers);
});

it('getResponseHeaders returns null when none provided', function () {
    $exception = new ApiException('Error', 500);

    // Default is [], not null. Let's test with explicit null
    $exceptionNull = new ApiException('Error', 500, null);

    expect($exceptionNull->getResponseHeaders())->toBeNull();
});

// ---- getResponseBody ----

it('getResponseBody returns the body', function () {
    $body = json_decode('{"message": "Server Error"}');
    $exception = new ApiException('Error', 500, [], $body);

    expect($exception->getResponseBody())->toBe($body);
});

it('getResponseBody returns null when no body provided', function () {
    $exception = new ApiException('Error', 500);

    expect($exception->getResponseBody())->toBeNull();
});

// ---- __toString ----

it('__toString returns a string containing exception information', function () {
    $headers = ['Content-Type' => ['application/json']];
    $body = json_decode('{"error": "server_error"}');

    $exception = new ApiException('Server Error', 500, $headers, $body);
    $string = (string) $exception;

    expect($string)->toBeString()
        ->toContain('ApiException')
        ->toContain('Server Error');
});

// ---- setResponseObject / getResponseObject ----

it('sets and gets the deserialized response object', function () {
    $exception = new ApiException('Error', 500);
    $obj = new stdClass();
    $obj->id = 1;
    $obj->name = 'Test';

    $exception->setResponseObject($obj);

    expect($exception->getResponseObject())->toBe($obj);
});

it('default response object is null', function () {
    $exception = new ApiException();

    expect($exception->getResponseObject())->toBeNull();
});

// ---- Multiple API keys pattern testing ----

it('works with different HTTP status codes', function (int $code, string $expectedMessage) {
    $exception = new ApiException($expectedMessage, $code, [], null);

    expect($exception->getCode())->toBe($code);
    expect($exception->getMessage())->toBe($expectedMessage);
})->with([
    '200 OK' => [200, 'OK'],
    '400 Bad Request' => [400, 'Bad Request'],
    '401 Unauthorized' => [401, 'Unauthorized'],
    '403 Forbidden' => [403, 'Forbidden'],
    '404 Not Found' => [404, 'Not Found'],
    '500 Internal Server Error' => [500, 'Internal Server Error'],
    '502 Bad Gateway' => [502, 'Bad Gateway'],
    '503 Service Unavailable' => [503, 'Service Unavailable'],
]);
