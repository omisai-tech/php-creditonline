<?php

use Omisai\CreditOnline\ApiException;

// ---------------------------------------------------------------------------
// Extension
// ---------------------------------------------------------------------------

it('extends Exception', function () {
    $e = new ApiException;
    expect($e)->toBeInstanceOf(Exception::class);
    expect($e)->toBeInstanceOf(ApiException::class);
});

// ---------------------------------------------------------------------------
// Constructor with all parameters
// ---------------------------------------------------------------------------

it('constructor accepts all parameters', function () {
    $headers = [
        'Content-Type' => ['application/json'],
        'X-Request-Id' => ['abc-123'],
    ];
    $body = (object) ['error' => 'Not Found', 'code' => 404];

    $e = new ApiException('Something went wrong', 404, $headers, $body);

    expect($e->getMessage())->toBe('Something went wrong');
    expect($e->getCode())->toBe(404);
    expect($e->getResponseHeaders())->toBe($headers);
    expect($e->getResponseBody())->toBe($body);
});

// ---------------------------------------------------------------------------
// Default parameter values
// ---------------------------------------------------------------------------

it('defaults message to empty string', function () {
    $e = new ApiException;
    expect($e->getMessage())->toBe('');
});

it('defaults code to 0', function () {
    $e = new ApiException;
    expect($e->getCode())->toBe(0);
});

it('defaults responseHeaders to empty array', function () {
    $e = new ApiException;
    expect($e->getResponseHeaders())->toBe([]);
});

it('defaults responseBody to null', function () {
    $e = new ApiException;
    expect($e->getResponseBody())->toBeNull();
});

// ---------------------------------------------------------------------------
// getResponseHeaders / getResponseBody with null
// ---------------------------------------------------------------------------

it('getResponseHeaders returns null when constructed with null', function () {
    $e = new ApiException('msg', 500, null, null);
    expect($e->getResponseHeaders())->toBeNull();
});

it('getResponseBody returns null when constructed with null', function () {
    $e = new ApiException('msg', 500, null, null);
    expect($e->getResponseBody())->toBeNull();
});

it('getResponseBody returns string body', function () {
    $e = new ApiException('msg', 500, [], 'Plain text error');
    expect($e->getResponseBody())->toBe('Plain text error');
});

it('getResponseBody returns stdClass body', function () {
    $body = (object) ['detail' => 'Forbidden'];
    $e = new ApiException('msg', 403, [], $body);
    expect($e->getResponseBody())->toBe($body);
});

// ---------------------------------------------------------------------------
// setResponseObject / getResponseObject
// ---------------------------------------------------------------------------

it('setResponseObject stores and getResponseObject retrieves', function () {
    $obj = new stdClass;
    $obj->key = 'value';

    $e = new ApiException;
    $e->setResponseObject($obj);

    expect($e->getResponseObject())->toBe($obj);
});

it('getResponseObject returns null by default', function () {
    $e = new ApiException;
    expect($e->getResponseObject())->toBeNull();
});

it('setResponseObject accepts any type', function ($value) {
    $e = new ApiException;
    $e->setResponseObject($value);
    expect($e->getResponseObject())->toBe($value);
})->with([
    'string' => 'some string',
    'int' => 42,
    'array' => [1, 2, 3],
    'stdClass' => fn () => (object) ['prop' => 'val'],
]);

// ---------------------------------------------------------------------------
// __toString
// ---------------------------------------------------------------------------

it('__toString contains exception class name and message', function () {
    $e = new ApiException('Test error message', 400);
    $output = (string) $e;

    expect($output)->toContain('ApiException');
    expect($output)->toContain('Test error message');
});

it('__toString includes stack trace', function () {
    $e = new ApiException('Failure', 500);
    $output = (string) $e;

    expect($output)->toContain('Stack trace');
    expect($output)->toContain('ApiExceptionTest.php');
});
