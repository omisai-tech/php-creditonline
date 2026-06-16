<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Omisai\CreditOnline\Api\GetDataByIdService;
use Omisai\CreditOnline\ApiException;
use Omisai\CreditOnline\Configuration;
use Omisai\CreditOnline\HeaderSelector;
use Omisai\CreditOnline\Model\ApiResult;

beforeEach(function () {
    $this->mock = new MockHandler;
    $this->handlerStack = HandlerStack::create($this->mock);
    $this->client = new Client(['handler' => $this->handlerStack]);
    $this->config = new Configuration;
    $this->api = new GetDataByIdService($this->client, $this->config);
});

it('can inject custom Guzzle ClientInterface', function () {
    $mock = new MockHandler([
        new Response(200, [], json_encode(['LimitReached' => false, 'Companies' => []])),
    ]);
    $client = new Client(['handler' => HandlerStack::create($mock)]);
    $api = new GetDataByIdService($client, $this->config);

    $result = $api->getData('test-token', '01-09-123456');

    expect($result)->toBeInstanceOf(ApiResult::class);
});

it('can inject custom Configuration', function () {
    $config = new Configuration;
    $config->setHost('https://custom.example.com/v2');

    $api = new GetDataByIdService(null, $config);

    expect($api->getConfig()->getHost())->toBe('https://custom.example.com/v2');
});

it('can inject custom HeaderSelector', function () {
    $selector = new HeaderSelector;
    $api = new GetDataByIdService(null, null, $selector);

    $request = $api->getDataRequest('token');

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
    expect(defined(GetDataByIdService::class.'::contentTypes'))->toBeTrue();
});

it('contentTypes has getData with application/json', function () {
    expect(GetDataByIdService::contentTypes)->toBeArray()
        ->toHaveKey('getData');
    expect(GetDataByIdService::contentTypes['getData'])->toBe(['application/json']);
});

it('getData with regnumber returns ApiResult model', function () {
    $this->mock->append(new Response(200, [], json_encode([
        'LimitReached' => false,
        'Companies' => [],
    ])));

    $result = $this->api->getData('valid-token', '01-09-123456');

    expect($result)->toBeInstanceOf(ApiResult::class);
    expect($result->getLimitReached())->toBeFalse();
    expect($result->getCompanies())->toBe([]);
});

it('getData with taxnumber returns ApiResult model', function () {
    $this->mock->append(new Response(200, [], json_encode([
        'LimitReached' => false,
        'Companies' => [
            [
                'Taxnumber' => '12345678-2-41',
                'Name' => 'Test Company Kft.',
            ],
        ],
    ])));

    $result = $this->api->getData('valid-token', null, '12345678-2-41');

    expect($result)->toBeInstanceOf(ApiResult::class);
    expect($result->getLimitReached())->toBeFalse();
    expect($result->getCompanies())->toBeArray()->toHaveCount(1);
});

it('getData with both regnumber and taxnumber returns ApiResult', function () {
    $this->mock->append(new Response(200, [], json_encode([
        'LimitReached' => false,
        'Companies' => [],
    ])));

    $result = $this->api->getData('token', '01-09-123456', '12345678-2-41');

    expect($result)->toBeInstanceOf(ApiResult::class);
});

it('getData with limitReached true', function () {
    $this->mock->append(new Response(200, [], json_encode([
        'LimitReached' => true,
        'Companies' => [],
    ])));

    $result = $this->api->getData('token', '01-09-999999');

    expect($result->getLimitReached())->toBeTrue();
});

it('throws InvalidArgumentException when token is null', function () {
    $this->api->getData(null, '01-09-123456');
})->throws(InvalidArgumentException::class, 'Missing the required parameter $token when calling getData');

it('throws InvalidArgumentException when token is empty array', function () {
    $this->api->getData([], '01-09-123456');
})->throws(InvalidArgumentException::class, 'Missing the required parameter $token when calling getData');

it('getDataRequest creates GET request to /Data', function () {
    $request = $this->api->getDataRequest('my-token');

    expect($request->getMethod())->toBe('GET');
    expect($request->getUri()->getPath())->toBe('/v3/Data');
    expect($request->getUri()->getHost())->toBe('api.creditonline.hu');
});

it('getDataRequest includes token query parameter', function () {
    $request = $this->api->getDataRequest('my-token');

    $query = $request->getUri()->getQuery();
    expect($query)->toContain('token=my-token');
});

it('getDataRequest includes regnumber query parameter', function () {
    $request = $this->api->getDataRequest('token', '01-09-123456');

    $query = $request->getUri()->getQuery();
    expect($query)->toContain('token=token');
    expect($query)->toContain('regnumber=01-09-123456');
});

it('getDataRequest includes taxnumber query parameter', function () {
    $request = $this->api->getDataRequest('token', null, '12345678-2-41');

    $query = $request->getUri()->getQuery();
    expect($query)->toContain('token=token');
    expect($query)->toContain('taxnumber=12345678-2-41');
});

it('getDataRequest includes all three query parameters', function () {
    $request = $this->api->getDataRequest('tok', '01-09-000001', '12345678-2-41');

    $query = $request->getUri()->getQuery();
    expect($query)->toContain('token=tok');
    expect($query)->toContain('regnumber=01-09-000001');
    expect($query)->toContain('taxnumber=12345678-2-41');
});

it('getDataRequest omits optional null parameters', function () {
    $request = $this->api->getDataRequest('token', null, null);

    $query = $request->getUri()->getQuery();
    expect($query)->toBe('token=token');
});

it('getDataWithHttpInfo returns [ApiResult, statusCode, headers]', function () {
    $this->mock->append(new Response(200, ['X-Response-Id' => 'abc123'], json_encode([
        'LimitReached' => false,
        'Companies' => [],
    ])));

    $result = $this->api->getDataWithHttpInfo('test-token', '01-09-123456');

    expect($result)->toBeArray()->toHaveCount(3);
    expect($result[0])->toBeInstanceOf(ApiResult::class);
    expect($result[1])->toBe(200);
    expect($result[2])->toBeArray();
    expect($result[2]['X-Response-Id'])->toBe(['abc123']);
});

it('getDataAsync returns a Promise', function () {
    $this->mock->append(new Response(200, [], json_encode([
        'LimitReached' => false,
        'Companies' => [],
    ])));

    $promise = $this->api->getDataAsync('test-token', '01-09-123456');

    expect($promise)->toBeInstanceOf(PromiseInterface::class);
});

it('getDataAsync resolves to ApiResult', function () {
    $this->mock->append(new Response(200, [], json_encode([
        'LimitReached' => false,
        'Companies' => [],
    ])));

    $result = $this->api->getDataAsync('test-token', '01-09-123456')->wait();

    expect($result)->toBeInstanceOf(ApiResult::class);
});

it('getDataAsyncWithHttpInfo returns a Promise', function () {
    $this->mock->append(new Response(200, [], json_encode([
        'LimitReached' => false,
        'Companies' => [],
    ])));

    $promise = $this->api->getDataAsyncWithHttpInfo('test-token', '01-09-123456');

    expect($promise)->toBeInstanceOf(PromiseInterface::class);
});

it('getDataAsyncWithHttpInfo resolves with array', function () {
    $this->mock->append(new Response(200, ['X-Header' => 'val'], json_encode([
        'LimitReached' => false,
        'Companies' => [],
    ])));

    $result = $this->api->getDataAsyncWithHttpInfo('test-token', '01-09-123456')->wait();

    expect($result)->toBeArray()->toHaveCount(3);
    expect($result[0])->toBeInstanceOf(ApiResult::class);
    expect($result[1])->toBe(200);
    expect($result[2])->toBeArray();
});

it('throws ApiException on 400 bad request', function () {
    $this->mock->append(new Response(400, [], json_encode(['error' => 'Bad Request'])));

    $this->api->getDataWithHttpInfo('bad-token', 'invalid');
})->throws(ApiException::class);

it('throws ApiException on 403 forbidden', function () {
    $this->mock->append(new Response(403, [], json_encode(['error' => 'Forbidden'])));

    $this->api->getDataWithHttpInfo('forbidden-token', '01-09-123456');
})->throws(ApiException::class);

it('throws ApiException on 500 server error', function () {
    $this->mock->append(new Response(500, [], json_encode(['error' => 'Server Error'])));

    $this->api->getDataWithHttpInfo('server-error-token', '01-09-123456');
})->throws(ApiException::class);

it('throws ApiException on connection failure', function () {
    $this->mock->append(new ConnectException('Connection timed out', new Request('GET', 'test')));

    $this->api->getDataWithHttpInfo('token', '01-09-123456');
})->throws(ApiException::class);

it('constructor with hostIndex defaults to 0', function () {
    $api = new GetDataByIdService(null, null, null, 0);

    expect($api->getHostIndex())->toBe(0);
});

it('constructor accepts custom hostIndex', function () {
    $api = new GetDataByIdService(null, null, null, 1);

    expect($api->getHostIndex())->toBe(1);
});

it('getDataRequest with custom contentType uses it in headers', function () {
    $request = $this->api->getDataRequest('token', '01-09-123456', null, 'application/xml');

    expect($request->getHeaderLine('Content-Type'))->toBe('application/xml');
});

it('getDataRequest allows empty string token', function () {
    $request = $this->api->getDataRequest('');

    expect($request)->toBeInstanceOf(Request::class);
    expect($request->getUri()->getQuery())->toContain('token=');
});

it('getDataRequest with null regnumber and null taxnumber only has token', function () {
    $request = $this->api->getDataRequest('tok', null, null);

    $query = $request->getUri()->getQuery();
    expect($query)->toBe('token=tok');
});

it('getDataWithHttpInfo fallback handles non-explicit status codes', function () {
    $this->mock->append(new Response(204, [], json_encode([
        'LimitReached' => false,
        'Companies' => [],
    ])));

    $result = $this->api->getDataWithHttpInfo('token', '01-09-123456');

    expect($result)->toBeArray()->toHaveCount(3);
    expect($result[1])->toBe(204);
});

it('throws ApiException on RequestException', function () {
    $this->mock->append(
        new RequestException(
            'Server error',
            new Request('GET', 'test'),
            new Response(502, [], json_encode(['error' => 'Bad Gateway']))
        )
    );

    $this->api->getDataWithHttpInfo('token', '01-09-123456');
})->throws(ApiException::class);

it('ApiException from RequestException captures status code and body', function () {
    $this->mock->append(
        new RequestException(
            'Validation failed',
            new Request('GET', 'test'),
            new Response(422, ['X-Reason' => 'invalid'], json_encode(['field' => 'regnumber']))
        )
    );

    try {
        $this->api->getDataWithHttpInfo('token', '01-09-123456');
    } catch (ApiException $e) {
        expect($e->getCode())->toBe(422);
        expect($e->getResponseBody())->toContain('regnumber');
        expect($e->getResponseHeaders()['X-Reason'])->toBe(['invalid']);
    }
});

it('ApiException from ConnectException has null body and headers', function () {
    $this->mock->append(new ConnectException('Timeout', new Request('GET', 'test')));

    try {
        $this->api->getDataWithHttpInfo('token', '01-09-123456');
    } catch (ApiException $e) {
        expect($e->getCode())->toBe(0);
        expect($e->getResponseBody())->toBeNull();
        expect($e->getResponseHeaders())->toBeNull();
    }
});

it('getDataAsyncWithHttpInfo rejects with ApiException on RequestException', function () {
    $this->mock->append(
        new RequestException(
            'Async failure',
            new Request('GET', 'test'),
            new Response(503, [], json_encode(['error' => 'Down']))
        )
    );

    $promise = $this->api->getDataAsyncWithHttpInfo('token', '01-09-123456');
    expect(fn () => $promise->wait())->toThrow(ApiException::class);
});

it('createHttpClientOption enables debug logging', function () {
    $tempFile = sys_get_temp_dir().'/creditonline-data-debug-'.uniqid().'.log';
    $this->config->setDebug(true);
    $this->config->setDebugFile($tempFile);

    $this->mock->append(new Response(200, [], json_encode([
        'LimitReached' => false,
        'Companies' => [],
    ])));

    $this->api->getDataWithHttpInfo('token', '01-09-123456');

    expect(file_exists($tempFile))->toBeTrue();
    unlink($tempFile);
});

it('createHttpClientOption with debug throws RuntimeException for bad path', function () {
    $this->config->setDebug(true);
    $this->config->setDebugFile('/root/forbidden/debug.log');

    $this->mock->append(new Response(200, [], json_encode([])));
    $this->api->getDataWithHttpInfo('token', '01-09-123456');
})->throws(RuntimeException::class);

it('createHttpClientOption sets cert and ssl_key', function () {
    $this->config->setCertFile('/fake/cert.pem');
    $this->config->setKeyFile('/fake/key.pem');

    $this->mock->append(new Response(200, [], json_encode([
        'LimitReached' => false,
        'Companies' => [],
    ])));

    $result = $this->api->getDataWithHttpInfo('token', '01-09-123456');

    expect($result[1])->toBe(200);
});

it('handleResponseWithDataType throws ApiException on invalid JSON for non-200 2xx', function () {
    $this->mock->append(new Response(204, ['Content-Type' => 'application/json'], 'not-valid-json{{{'));
    $this->api->getDataWithHttpInfo('token', '01-09-123456');
})->throws(ApiException::class, 'Error JSON decoding server response');
