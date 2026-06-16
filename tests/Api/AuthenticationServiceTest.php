<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Omisai\CreditOnline\Api\AuthenticationService;
use Omisai\CreditOnline\ApiException;
use Omisai\CreditOnline\Configuration;
use Omisai\CreditOnline\HeaderSelector;

beforeEach(function () {
    $this->mock = new MockHandler;
    $this->handlerStack = HandlerStack::create($this->mock);
    $this->client = new Client(['handler' => $this->handlerStack]);
    $this->config = new Configuration;
    $this->api = new AuthenticationService($this->client, $this->config);
});

it('creates with default client, config, and header selector', function () {
    $api = new AuthenticationService;

    expect($api->getConfig())->toBeInstanceOf(Configuration::class);
    expect($api->getConfig()->getHost())->toBe('https://api.creditonline.hu/v3');
});

it('can inject custom Guzzle ClientInterface', function () {
    $mock = new MockHandler([
        new Response(200, [], json_encode([])),
    ]);
    $client = new Client(['handler' => HandlerStack::create($mock)]);
    $api = new AuthenticationService($client, $this->config);

    $result = $api->getTokenWithHttpInfo('test-api-key');

    expect($result[1])->toBe(200);
});

it('can inject custom Configuration', function () {
    $config = new Configuration;
    $config->setHost('https://custom.example.com/v2');

    $api = new AuthenticationService(null, $config);

    expect($api->getConfig()->getHost())->toBe('https://custom.example.com/v2');
});

it('can inject custom HeaderSelector', function () {
    $selector = new HeaderSelector;
    $api = new AuthenticationService(null, null, $selector);

    $request = $api->getTokenRequest('api-key');

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
    expect(defined(AuthenticationService::class.'::contentTypes'))->toBeTrue();
});

it('contentTypes has getToken with application/json', function () {
    expect(AuthenticationService::contentTypes)->toBeArray()
        ->toHaveKey('getToken');
    expect(AuthenticationService::contentTypes['getToken'])->toBe(['application/json']);
});

it('getTokenWithHttpInfo returns [null, statusCode, headers]', function () {
    $this->mock->append(new Response(200, ['X-Custom' => 'bar'], ''));

    $result = $this->api->getTokenWithHttpInfo('test-api-key');

    expect($result)->toBeArray()->toHaveCount(3);
    expect($result[0])->toBeNull();
    expect($result[1])->toBe(200);
    expect($result[2])->toBeArray();
    expect($result[2]['X-Custom'])->toBe(['bar']);
});

it('getToken returns void on successful response', function () {
    $this->mock->append(new Response(200, [], ''));

    $result = $this->api->getToken('test-api-key');

    expect($result)->toBeNull();
});

it('getToken passes optional parameters', function () {
    $this->mock->append(new Response(200, [], ''));

    $result = $this->api->getToken('test-api-key', 'xml', 'en');

    expect($result)->toBeNull();
});

it('getTokenRequest creates GET request to /Token', function () {
    $request = $this->api->getTokenRequest('my-api-key');

    expect($request->getMethod())->toBe('GET');
    expect($request->getUri()->getPath())->toBe('/v3/Token');
    expect($request->getUri()->getHost())->toBe('api.creditonline.hu');
});

it('getTokenRequest includes api_key query parameter', function () {
    $request = $this->api->getTokenRequest('my-api-key');

    $query = $request->getUri()->getQuery();
    expect($query)->toContain('apiKey=my-api-key');
});

it('getTokenRequest includes optional format and language query params', function () {
    $request = $this->api->getTokenRequest('key123', 'xml', 'en');

    $query = $request->getUri()->getQuery();
    expect($query)->toContain('apiKey=key123');
    expect($query)->toContain('format=xml');
    expect($query)->toContain('language=en');
});

it('getTokenRequest throws InvalidArgumentException when api_key is null', function () {
    $this->api->getTokenRequest(null);
})->throws(InvalidArgumentException::class, 'Missing the required parameter $api_key when calling getToken');

it('getTokenRequest throws InvalidArgumentException when api_key is empty array', function () {
    $this->api->getTokenRequest([]);
})->throws(InvalidArgumentException::class, 'Missing the required parameter $api_key when calling getToken');

it('getTokenRequest uses custom content type from self::contentTypes', function () {
    $request = $this->api->getTokenRequest('key', 'json', 'hu', 'application/json');

    expect($request->getHeaderLine('Content-Type'))->toBe('application/json');
});

it('getTokenRequest uses custom host from configuration', function () {
    $config = new Configuration;
    $config->setHost('https://custom.example.com/v1');
    $api = new AuthenticationService($this->client, $config);

    $request = $api->getTokenRequest('key');

    expect($request->getUri()->getHost())->toBe('custom.example.com');
});

it('getTokenAsync returns a Promise', function () {
    $this->mock->append(new Response(200, [], ''));

    $promise = $this->api->getTokenAsync('test-api-key');

    expect($promise)->toBeInstanceOf(PromiseInterface::class);
});

it('getTokenAsync resolves to null', function () {
    $this->mock->append(new Response(200, [], ''));

    $result = $this->api->getTokenAsync('test-api-key')->wait();

    expect($result)->toBeNull();
});

it('getTokenAsyncWithHttpInfo returns a Promise', function () {
    $this->mock->append(new Response(200, [], ''));

    $promise = $this->api->getTokenAsyncWithHttpInfo('test-api-key');

    expect($promise)->toBeInstanceOf(PromiseInterface::class);
});

it('getTokenAsyncWithHttpInfo resolves with array', function () {
    $this->mock->append(new Response(200, ['X-Token-Header' => 'abc'], ''));

    $result = $this->api->getTokenAsyncWithHttpInfo('test-api-key')->wait();

    expect($result)->toBeArray()->toHaveCount(3);
    expect($result[0])->toBeNull();
    expect($result[1])->toBe(200);
    expect($result[2])->toBeArray();
});

it('throws ApiException on non-2xx response', function () {
    $this->mock->append(new Response(400, [], json_encode(['error' => 'Bad Request'])));

    $this->api->getTokenWithHttpInfo('bad-key');
})->throws(ApiException::class);

it('throws ApiException on 401 unauthorized response', function () {
    $this->mock->append(new Response(401, [], json_encode(['error' => 'Unauthorized'])));

    $this->api->getTokenWithHttpInfo('invalid-key');
})->throws(ApiException::class);

it('throws ApiException on 404 not found response', function () {
    $this->mock->append(new Response(404, [], json_encode(['error' => 'Not Found'])));

    $this->api->getTokenWithHttpInfo('missing-key');
})->throws(ApiException::class);

it('throws ApiException on 500 server error response', function () {
    $this->mock->append(new Response(500, [], json_encode(['error' => 'Internal Server Error'])));

    $this->api->getTokenWithHttpInfo('server-error-key');
})->throws(ApiException::class);

it('throws ApiException on connection failure', function () {
    $this->mock->append(new ConnectException('Connection refused', new Request('GET', 'test')));

    $this->api->getTokenWithHttpInfo('test-key');
})->throws(ApiException::class);

it('constructor with hostIndex defaults to 0', function () {
    $api = new AuthenticationService(null, null, null, 0);

    expect($api->getHostIndex())->toBe(0);
});

it('constructor accepts custom hostIndex', function () {
    $api = new AuthenticationService(null, null, null, 1);

    expect($api->getHostIndex())->toBe(1);
});

it('getTokenWithHttpInfo handles api_key with special characters', function () {
    $this->mock->append(new Response(200, [], ''));

    $result = $this->api->getTokenWithHttpInfo('key/with=symbols&chars');

    expect($result[1])->toBe(200);
});

it('getTokenRequest with null format omits format from query', function () {
    $request = $this->api->getTokenRequest('key', null, 'hu');

    $query = $request->getUri()->getQuery();
    expect($query)->toContain('apiKey=key');
    expect($query)->not->toContain('format=');
    expect($query)->toContain('language=hu');
});

it('getTokenRequest with null language omits language from query', function () {
    $request = $this->api->getTokenRequest('key', 'json', null);

    $query = $request->getUri()->getQuery();
    expect($query)->toContain('apiKey=key');
    expect($query)->toContain('format=json');
    expect($query)->not->toContain('language=');
});

it('getTokenRequest with null format and null language only includes apiKey', function () {
    $request = $this->api->getTokenRequest('key', null, null);

    $query = $request->getUri()->getQuery();
    expect($query)->toBe('apiKey=key');
});

it('getTokenRequest allows empty string api_key', function () {
    $request = $this->api->getTokenRequest('');

    expect($request)->toBeInstanceOf(Request::class);
    expect($request->getUri()->getQuery())->toContain('apiKey=');
});

it('createHttpClientOption sets debug when config has debug enabled', function () {
    $tempFile = sys_get_temp_dir().'/creditonline-debug-'.uniqid().'.log';
    $this->config->setDebug(true);
    $this->config->setDebugFile($tempFile);

    $this->mock->append(new Response(200, [], ''));
    $this->api->getTokenWithHttpInfo('key');

    expect(file_exists($tempFile))->toBeTrue();
    unlink($tempFile);
});

it('createHttpClientOption throws RuntimeException when debug file cannot be opened', function () {
    $this->config->setDebug(true);
    $this->config->setDebugFile('/nonexistent/path/debug.log');

    $this->mock->append(new Response(200, [], ''));
    $this->api->getTokenWithHttpInfo('key');
})->throws(RuntimeException::class, 'Failed to open the debug file');

it('createHttpClientOption sets cert and ssl_key options', function () {
    $this->config->setCertFile('/path/to/cert.pem');
    $this->config->setKeyFile('/path/to/key.pem');

    $this->mock->append(new Response(200, [], ''));
    $result = $this->api->getTokenWithHttpInfo('key');

    expect($result[1])->toBe(200);
});

it('throws ApiException on RequestException', function () {
    $this->mock->append(
        new RequestException(
            'Request error',
            new Request('GET', 'test'),
            new Response(502, [], json_encode(['error' => 'Bad Gateway']))
        )
    );

    $this->api->getTokenWithHttpInfo('key');
})->throws(ApiException::class);

it('ApiException from RequestException contains response body', function () {
    $this->mock->append(
        new RequestException(
            'Request error',
            new Request('GET', 'test'),
            new Response(422, ['X-Error' => 'validation'], json_encode(['detail' => 'Invalid input']))
        )
    );

    try {
        $this->api->getTokenWithHttpInfo('key');
    } catch (ApiException $e) {
        expect($e->getCode())->toBe(422);
        expect($e->getResponseBody())->toContain('Invalid input');
        expect($e->getResponseHeaders()['X-Error'])->toBe(['validation']);
    }
});

it('ApiException from ConnectException has null response body and headers', function () {
    $this->mock->append(new ConnectException('Connection refused', new Request('GET', 'test')));

    try {
        $this->api->getTokenWithHttpInfo('key');
    } catch (ApiException $e) {
        expect($e->getCode())->toBe(0);
        expect($e->getResponseBody())->toBeNull();
        expect($e->getResponseHeaders())->toBeNull();
    }
});

it('getTokenAsyncWithHttpInfo rejects with ApiException on RequestException', function () {
    $this->mock->append(
        new RequestException(
            'Async error',
            new Request('GET', 'test'),
            new Response(503, [], json_encode(['error' => 'Service Unavailable']))
        )
    );

    $promise = $this->api->getTokenAsyncWithHttpInfo('key');
    expect(fn () => $promise->wait())->toThrow(ApiException::class);
});
