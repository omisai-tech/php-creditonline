<?php

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Omisai\CreditOnline\Api\TokenGenerlsApi;
use Omisai\CreditOnline\ApiException;
use Omisai\CreditOnline\Configuration;
use Omisai\CreditOnline\HeaderSelector;

beforeEach(function () {
    $this->mock = new MockHandler();
    $this->handlerStack = HandlerStack::create($this->mock);
    $this->client = new Client(['handler' => $this->handlerStack]);
    $this->config = new Configuration();
    $this->api = new TokenGenerlsApi($this->client, $this->config);
});

it('creates with default client, config, and header selector', function () {
    $api = new TokenGenerlsApi();

    expect($api->getConfig())->toBeInstanceOf(Configuration::class);
    expect($api->getConfig()->getHost())->toBe('https://api.creditonline.hu/v3');
});

it('can inject custom Guzzle ClientInterface', function () {
    $mock = new MockHandler([
        new Response(200, [], json_encode([])),
    ]);
    $client = new Client(['handler' => HandlerStack::create($mock)]);
    $api = new TokenGenerlsApi($client, $this->config);

    $result = $api->tokenGetWithHttpInfo('test-api-key');

    expect($result[1])->toBe(200);
});

it('can inject custom Configuration', function () {
    $config = new Configuration();
    $config->setHost('https://custom.example.com/v2');

    $api = new TokenGenerlsApi(null, $config);

    expect($api->getConfig()->getHost())->toBe('https://custom.example.com/v2');
});

it('can inject custom HeaderSelector', function () {
    $selector = new HeaderSelector();
    $api = new TokenGenerlsApi(null, null, $selector);

    $request = $api->tokenGetRequest('api-key');

    expect($request)->toBeInstanceOf(Request::class);
});

it('setHostIndex sets a custom host index', function () {
    $this->api->setHostIndex(1);

    expect($this->api->getHostIndex())->toBe(1);
});

it('getHostIndex defaults to 0', function () {
    expect($this->api->getHostIndex())->toBe(0);
});

it('getConfig returns the Configuration instance', function () {
    expect($this->api->getConfig())->toBe($this->config);
});

it('has contentTypes static property', function () {
    expect(defined(TokenGenerlsApi::class . '::contentTypes'))->toBeTrue();
});

it('contentTypes has tokenGet with application/json', function () {
    expect(TokenGenerlsApi::contentTypes)->toBeArray()
        ->toHaveKey('tokenGet');
    expect(TokenGenerlsApi::contentTypes['tokenGet'])->toBe(['application/json']);
});

it('tokenGetWithHttpInfo returns [null, statusCode, headers]', function () {
    $this->mock->append(new Response(200, ['X-Custom' => 'bar'], ''));

    $result = $this->api->tokenGetWithHttpInfo('test-api-key');

    expect($result)->toBeArray()->toHaveCount(3);
    expect($result[0])->toBeNull();
    expect($result[1])->toBe(200);
    expect($result[2])->toBeArray();
    expect($result[2]['X-Custom'])->toBe(['bar']);
});

it('tokenGet returns void on successful response', function () {
    $this->mock->append(new Response(200, [], ''));

    $result = $this->api->tokenGet('test-api-key');

    expect($result)->toBeNull();
});

it('tokenGet passes optional parameters', function () {
    $this->mock->append(new Response(200, [], ''));

    $result = $this->api->tokenGet('test-api-key', 'xml', 'en');

    expect($result)->toBeNull();
});

it('tokenGetRequest creates GET request to /Token', function () {
    $request = $this->api->tokenGetRequest('my-api-key');

    expect($request->getMethod())->toBe('GET');
    expect($request->getUri()->getPath())->toBe('/v3/Token');
    expect($request->getUri()->getHost())->toBe('api.creditonline.hu');
});

it('tokenGetRequest includes api_key query parameter', function () {
    $request = $this->api->tokenGetRequest('my-api-key');

    $query = $request->getUri()->getQuery();
    expect($query)->toContain('apiKey=my-api-key');
});

it('tokenGetRequest includes optional format and language query params', function () {
    $request = $this->api->tokenGetRequest('key123', 'xml', 'en');

    $query = $request->getUri()->getQuery();
    expect($query)->toContain('apiKey=key123');
    expect($query)->toContain('format=xml');
    expect($query)->toContain('language=en');
});

it('tokenGetRequest throws InvalidArgumentException when api_key is null', function () {
    $this->api->tokenGetRequest(null);
})->throws(\InvalidArgumentException::class, 'Missing the required parameter $api_key when calling tokenGet');

it('tokenGetRequest throws InvalidArgumentException when api_key is empty array', function () {
    $this->api->tokenGetRequest([]);
})->throws(\InvalidArgumentException::class, 'Missing the required parameter $api_key when calling tokenGet');

it('tokenGetRequest uses custom content type from self::contentTypes', function () {
    $request = $this->api->tokenGetRequest('key', 'json', 'hu', 'application/json');

    expect($request->getHeaderLine('Content-Type'))->toBe('application/json');
});

it('tokenGetRequest uses custom host from configuration', function () {
    $config = new Configuration();
    $config->setHost('https://custom.example.com/v1');
    $api = new TokenGenerlsApi($this->client, $config);

    $request = $api->tokenGetRequest('key');

    expect($request->getUri()->getHost())->toBe('custom.example.com');
});

it('tokenGetAsync returns a Promise', function () {
    $this->mock->append(new Response(200, [], ''));

    $promise = $this->api->tokenGetAsync('test-api-key');

    expect($promise)->toBeInstanceOf(PromiseInterface::class);
});

it('tokenGetAsync resolves to null', function () {
    $this->mock->append(new Response(200, [], ''));

    $result = $this->api->tokenGetAsync('test-api-key')->wait();

    expect($result)->toBeNull();
});

it('tokenGetAsyncWithHttpInfo returns a Promise', function () {
    $this->mock->append(new Response(200, [], ''));

    $promise = $this->api->tokenGetAsyncWithHttpInfo('test-api-key');

    expect($promise)->toBeInstanceOf(PromiseInterface::class);
});

it('tokenGetAsyncWithHttpInfo resolves with array', function () {
    $this->mock->append(new Response(200, ['X-Token-Header' => 'abc'], ''));

    $result = $this->api->tokenGetAsyncWithHttpInfo('test-api-key')->wait();

    expect($result)->toBeArray()->toHaveCount(3);
    expect($result[0])->toBeNull();
    expect($result[1])->toBe(200);
    expect($result[2])->toBeArray();
});

it('throws ApiException on non-2xx response', function () {
    $this->mock->append(new Response(400, [], json_encode(['error' => 'Bad Request'])));

    $this->api->tokenGetWithHttpInfo('bad-key');
})->throws(ApiException::class);

it('throws ApiException on 401 unauthorized response', function () {
    $this->mock->append(new Response(401, [], json_encode(['error' => 'Unauthorized'])));

    $this->api->tokenGetWithHttpInfo('invalid-key');
})->throws(ApiException::class);

it('throws ApiException on 404 not found response', function () {
    $this->mock->append(new Response(404, [], json_encode(['error' => 'Not Found'])));

    $this->api->tokenGetWithHttpInfo('missing-key');
})->throws(ApiException::class);

it('throws ApiException on 500 server error response', function () {
    $this->mock->append(new Response(500, [], json_encode(['error' => 'Internal Server Error'])));

    $this->api->tokenGetWithHttpInfo('server-error-key');
})->throws(ApiException::class);

it('throws ApiException on connection failure', function () {
    $this->mock->append(new ConnectException('Connection refused', new Request('GET', 'test')));

    $this->api->tokenGetWithHttpInfo('test-key');
})->throws(ApiException::class);

it('constructor with hostIndex defaults to 0', function () {
    $api = new TokenGenerlsApi(null, null, null, 0);

    expect($api->getHostIndex())->toBe(0);
});

it('constructor accepts custom hostIndex', function () {
    $api = new TokenGenerlsApi(null, null, null, 1);

    expect($api->getHostIndex())->toBe(1);
});

it('tokenGetWithHttpInfo handles api_key with special characters', function () {
    $this->mock->append(new Response(200, [], ''));

    $result = $this->api->tokenGetWithHttpInfo('key/with=symbols&chars');

    expect($result[1])->toBe(200);
});

it('tokenGetRequest with null format omits format from query', function () {
    $request = $this->api->tokenGetRequest('key', null, 'hu');

    $query = $request->getUri()->getQuery();
    expect($query)->toContain('apiKey=key');
    expect($query)->not->toContain('format=');
    expect($query)->toContain('language=hu');
});

it('tokenGetRequest with null language omits language from query', function () {
    $request = $this->api->tokenGetRequest('key', 'json', null);

    $query = $request->getUri()->getQuery();
    expect($query)->toContain('apiKey=key');
    expect($query)->toContain('format=json');
    expect($query)->not->toContain('language=');
});

it('tokenGetRequest with null format and null language only includes apiKey', function () {
    $request = $this->api->tokenGetRequest('key', null, null);

    $query = $request->getUri()->getQuery();
    expect($query)->toBe('apiKey=key');
});

it('tokenGetRequest allows empty string api_key', function () {
    $request = $this->api->tokenGetRequest('');

    expect($request)->toBeInstanceOf(Request::class);
    expect($request->getUri()->getQuery())->toContain('apiKey=');
});

it('createHttpClientOption sets debug when config has debug enabled', function () {
    $tempFile = sys_get_temp_dir() . '/creditonline-debug-' . uniqid() . '.log';
    $this->config->setDebug(true);
    $this->config->setDebugFile($tempFile);

    $this->mock->append(new Response(200, [], ''));
    $this->api->tokenGetWithHttpInfo('key');

    expect(file_exists($tempFile))->toBeTrue();
    unlink($tempFile);
});

it('createHttpClientOption throws RuntimeException when debug file cannot be opened', function () {
    $this->config->setDebug(true);
    $this->config->setDebugFile('/nonexistent/path/debug.log');

    $this->mock->append(new Response(200, [], ''));
    $this->api->tokenGetWithHttpInfo('key');
})->throws(\RuntimeException::class, 'Failed to open the debug file');

it('createHttpClientOption sets cert and ssl_key options', function () {
    $this->config->setCertFile('/path/to/cert.pem');
    $this->config->setKeyFile('/path/to/key.pem');

    $this->mock->append(new Response(200, [], ''));
    $result = $this->api->tokenGetWithHttpInfo('key');

    expect($result[1])->toBe(200);
});

it('throws ApiException on RequestException', function () {
    $this->mock->append(
        new \GuzzleHttp\Exception\RequestException(
            'Request error',
            new Request('GET', 'test'),
            new Response(502, [], json_encode(['error' => 'Bad Gateway']))
        )
    );

    $this->api->tokenGetWithHttpInfo('key');
})->throws(ApiException::class);

it('ApiException from RequestException contains response body', function () {
    $this->mock->append(
        new \GuzzleHttp\Exception\RequestException(
            'Request error',
            new Request('GET', 'test'),
            new Response(422, ['X-Error' => 'validation'], json_encode(['detail' => 'Invalid input']))
        )
    );

    try {
        $this->api->tokenGetWithHttpInfo('key');
    } catch (ApiException $e) {
        expect($e->getCode())->toBe(422);
        expect($e->getResponseBody())->toContain('Invalid input');
        expect($e->getResponseHeaders()['X-Error'])->toBe(['validation']);
    }
});

it('ApiException from ConnectException has null response body and headers', function () {
    $this->mock->append(new ConnectException('Connection refused', new Request('GET', 'test')));

    try {
        $this->api->tokenGetWithHttpInfo('key');
    } catch (ApiException $e) {
        expect($e->getCode())->toBe(0);
        expect($e->getResponseBody())->toBeNull();
        expect($e->getResponseHeaders())->toBeNull();
    }
});

it('tokenGetAsyncWithHttpInfo rejects with ApiException on RequestException', function () {
    $this->mock->append(
        new \GuzzleHttp\Exception\RequestException(
            'Async error',
            new Request('GET', 'test'),
            new Response(503, [], json_encode(['error' => 'Service Unavailable']))
        )
    );

    $promise = $this->api->tokenGetAsyncWithHttpInfo('key');
    expect(fn () => $promise->wait())->toThrow(ApiException::class);
});
