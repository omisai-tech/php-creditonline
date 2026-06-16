<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Omisai\CreditOnline\Api\GetProfileAndTrafficDataService;
use Omisai\CreditOnline\ApiException;
use Omisai\CreditOnline\Configuration;
use Omisai\CreditOnline\HeaderSelector;
use Omisai\CreditOnline\Model\Profile;

beforeEach(function () {
    $this->mock = new MockHandler;
    $this->handlerStack = HandlerStack::create($this->mock);
    $this->client = new Client(['handler' => $this->handlerStack]);
    $this->config = new Configuration;
    $this->api = new GetProfileAndTrafficDataService($this->client, $this->config);
});

it('can inject custom Guzzle ClientInterface', function () {
    $mock = new MockHandler([
        new Response(200, [], json_encode([
            'CompanyName' => 'Test Co',
            'ActualFormat' => 'json',
            'ActualLanguage' => 'hu',
        ])),
    ]);
    $client = new Client(['handler' => HandlerStack::create($mock)]);
    $api = new GetProfileAndTrafficDataService($client, $this->config);

    $result = $api->getProfile('test-token');

    expect($result)->toBeInstanceOf(Profile::class);
});

it('can inject custom Configuration', function () {
    $config = new Configuration;
    $config->setHost('https://custom.example.com/v2');

    $api = new GetProfileAndTrafficDataService(null, $config);

    expect($api->getConfig()->getHost())->toBe('https://custom.example.com/v2');
});

it('can inject custom HeaderSelector', function () {
    $selector = new HeaderSelector;
    $api = new GetProfileAndTrafficDataService(null, null, $selector);

    $request = $api->getProfileRequest('token');

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
    expect(defined(GetProfileAndTrafficDataService::class.'::contentTypes'))->toBeTrue();
});

it('contentTypes has getProfile with application/json', function () {
    expect(GetProfileAndTrafficDataService::contentTypes)->toBeArray()
        ->toHaveKey('getProfile');
    expect(GetProfileAndTrafficDataService::contentTypes['getProfile'])->toBe(['application/json']);
});

it('getProfile returns Profile model', function () {
    $this->mock->append(new Response(200, [], json_encode([
        'CompanyName' => 'Test Company Kft.',
        'ActualFormat' => 'json',
        'ActualLanguage' => 'hu',
    ])));

    $result = $this->api->getProfile('test-token');

    expect($result)->toBeInstanceOf(Profile::class);
    expect($result->getCompanyName())->toBe('Test Company Kft.');
    expect($result->getActualFormat())->toBe('json');
    expect($result->getActualLanguage())->toBe('hu');
});

it('getProfile returns Profile with different language', function () {
    $this->mock->append(new Response(200, [], json_encode([
        'CompanyName' => 'Nemzetkozi Kft.',
        'ActualFormat' => 'xml',
        'ActualLanguage' => 'en',
    ])));

    $result = $this->api->getProfile('test-token');

    expect($result->getCompanyName())->toBe('Nemzetkozi Kft.');
    expect($result->getActualFormat())->toBe('xml');
    expect($result->getActualLanguage())->toBe('en');
});

it('getProfile returns Profile with ActualUsages', function () {
    $this->mock->append(new Response(200, [], json_encode([
        'CompanyName' => 'Usage Co',
        'ActualFormat' => 'json',
        'ActualLanguage' => 'hu',
        'ActualUsages' => [
            'Ids' => ['id1', 'id2'],
            'Limit' => 100,
            'Type' => 'basic',
        ],
    ])));

    $result = $this->api->getProfile('test-token');

    expect($result)->toBeInstanceOf(Profile::class);
    expect($result->getCompanyName())->toBe('Usage Co');
    expect($result->getActualUsages())->not->toBeNull();
});

it('getProfileWithHttpInfo returns array with status code', function () {
    $this->mock->append(new Response(200, ['X-Profile-Id' => 'prof123'], json_encode([
        'CompanyName' => 'Profile Test Kft.',
        'ActualFormat' => 'json',
        'ActualLanguage' => 'hu',
    ])));

    $result = $this->api->getProfileWithHttpInfo('test-token');

    expect($result)->toBeArray()->toHaveCount(3);
    expect($result[0])->toBeInstanceOf(Profile::class);
    expect($result[0]->getCompanyName())->toBe('Profile Test Kft.');
    expect($result[1])->toBe(200);
    expect($result[2])->toBeArray();
    expect($result[2]['X-Profile-Id'])->toBe(['prof123']);
});

it('getProfileAsync returns a Promise', function () {
    $this->mock->append(new Response(200, [], json_encode([
        'CompanyName' => 'Async Co',
        'ActualFormat' => 'json',
        'ActualLanguage' => 'hu',
    ])));

    $promise = $this->api->getProfileAsync('test-token');

    expect($promise)->toBeInstanceOf(PromiseInterface::class);
});

it('getProfileAsync resolves to Profile', function () {
    $this->mock->append(new Response(200, [], json_encode([
        'CompanyName' => 'Async Test Kft.',
        'ActualFormat' => 'json',
        'ActualLanguage' => 'en',
    ])));

    $result = $this->api->getProfileAsync('test-token')->wait();

    expect($result)->toBeInstanceOf(Profile::class);
    expect($result->getCompanyName())->toBe('Async Test Kft.');
});

it('getProfileAsyncWithHttpInfo returns a Promise', function () {
    $this->mock->append(new Response(200, [], json_encode([
        'CompanyName' => 'AsyncInfo Co',
        'ActualFormat' => 'json',
        'ActualLanguage' => 'hu',
    ])));

    $promise = $this->api->getProfileAsyncWithHttpInfo('test-token');

    expect($promise)->toBeInstanceOf(PromiseInterface::class);
});

it('getProfileAsyncWithHttpInfo resolves with array', function () {
    $this->mock->append(new Response(200, ['X-Header' => 'val'], json_encode([
        'CompanyName' => 'AsyncInfo Test Kft.',
        'ActualFormat' => 'json',
        'ActualLanguage' => 'hu',
    ])));

    $result = $this->api->getProfileAsyncWithHttpInfo('test-token')->wait();

    expect($result)->toBeArray()->toHaveCount(3);
    expect($result[0])->toBeInstanceOf(Profile::class);
    expect($result[1])->toBe(200);
    expect($result[2])->toBeArray();
});

it('getProfileRequest creates GET request to /Profile', function () {
    $request = $this->api->getProfileRequest('my-token');

    expect($request->getMethod())->toBe('GET');
    expect($request->getUri()->getPath())->toBe('/v3/Profile');
    expect($request->getUri()->getHost())->toBe('api.creditonline.hu');
});

it('getProfileRequest includes token query parameter', function () {
    $request = $this->api->getProfileRequest('my-token');

    $query = $request->getUri()->getQuery();
    expect($query)->toContain('token=my-token');
});

it('getProfileRequest throws InvalidArgumentException when token is null', function () {
    $this->api->getProfileRequest(null);
})->throws(InvalidArgumentException::class, 'Missing the required parameter $token when calling getProfile');

it('getProfileRequest throws InvalidArgumentException when token is empty array', function () {
    $this->api->getProfileRequest([]);
})->throws(InvalidArgumentException::class, 'Missing the required parameter $token when calling getProfile');

it('throws ApiException on 400 bad request', function () {
    $this->mock->append(new Response(400, [], json_encode(['error' => 'Bad Request'])));

    $this->api->getProfileWithHttpInfo('bad-token');
})->throws(ApiException::class);

it('throws ApiException on 403 forbidden', function () {
    $this->mock->append(new Response(403, [], json_encode(['error' => 'Forbidden'])));

    $this->api->getProfileWithHttpInfo('forbidden-token');
})->throws(ApiException::class);

it('throws ApiException on 500 server error', function () {
    $this->mock->append(new Response(500, [], json_encode(['error' => 'Server Error'])));

    $this->api->getProfileWithHttpInfo('server-error-token');
})->throws(ApiException::class);

it('throws ApiException on connection failure', function () {
    $this->mock->append(new ConnectException('Connection refused', new Request('GET', 'test')));

    $this->api->getProfileWithHttpInfo('test-token');
})->throws(ApiException::class);

it('constructor with hostIndex defaults to 0', function () {
    $api = new GetProfileAndTrafficDataService(null, null, null, 0);

    expect($api->getHostIndex())->toBe(0);
});

it('constructor accepts custom hostIndex', function () {
    $api = new GetProfileAndTrafficDataService(null, null, null, 1);

    expect($api->getHostIndex())->toBe(1);
});

it('getProfileRequest with custom contentType uses it in headers', function () {
    $request = $this->api->getProfileRequest('token', 'application/xml');

    expect($request->getHeaderLine('Content-Type'))->toBe('application/xml');
});

it('getProfileRequest allows empty string token', function () {
    $request = $this->api->getProfileRequest('');

    expect($request)->toBeInstanceOf(Request::class);
    expect($request->getUri()->getQuery())->toContain('token=');
});

it('getProfileWithHttpInfo handles 201 non-200 2xx response', function () {
    $this->mock->append(new Response(201, ['X-Created' => 'yes'], json_encode([
        'CompanyName' => 'Created Co',
        'ActualFormat' => 'json',
        'ActualLanguage' => 'hu',
    ])));

    $result = $this->api->getProfileWithHttpInfo('token');

    expect($result[0])->toBeInstanceOf(Profile::class);
    expect($result[1])->toBe(201);
});

it('throws ApiException on RequestException', function () {
    $this->mock->append(
        new RequestException(
            'Server error',
            new Request('GET', 'test'),
            new Response(502, [], json_encode(['error' => 'Bad Gateway']))
        )
    );

    $this->api->getProfileWithHttpInfo('token');
})->throws(ApiException::class);

it('ApiException from RequestException captures status code and body', function () {
    $this->mock->append(
        new RequestException(
            'Validation failed',
            new Request('GET', 'test'),
            new Response(422, ['X-Reason' => 'invalid'], json_encode(['field' => 'token']))
        )
    );

    try {
        $this->api->getProfileWithHttpInfo('token');
    } catch (ApiException $e) {
        expect($e->getCode())->toBe(422);
        expect($e->getResponseBody())->toContain('token');
        expect($e->getResponseHeaders()['X-Reason'])->toBe(['invalid']);
    }
});

it('ApiException from ConnectException has null body and headers', function () {
    $this->mock->append(new ConnectException('Timeout', new Request('GET', 'test')));

    try {
        $this->api->getProfileWithHttpInfo('token');
    } catch (ApiException $e) {
        expect($e->getCode())->toBe(0);
        expect($e->getResponseBody())->toBeNull();
        expect($e->getResponseHeaders())->toBeNull();
    }
});

it('getProfileAsyncWithHttpInfo rejects with ApiException on RequestException', function () {
    $this->mock->append(
        new RequestException(
            'Async failure',
            new Request('GET', 'test'),
            new Response(503, [], json_encode(['error' => 'Down']))
        )
    );

    $promise = $this->api->getProfileAsyncWithHttpInfo('token');
    expect(fn () => $promise->wait())->toThrow(ApiException::class);
});

it('createHttpClientOption enables debug logging', function () {
    $tempFile = sys_get_temp_dir().'/creditonline-profile-debug-'.uniqid().'.log';
    $this->config->setDebug(true);
    $this->config->setDebugFile($tempFile);

    $this->mock->append(new Response(200, [], json_encode([
        'CompanyName' => 'Debug Co',
        'ActualFormat' => 'json',
        'ActualLanguage' => 'hu',
    ])));

    $this->api->getProfileWithHttpInfo('token');

    expect(file_exists($tempFile))->toBeTrue();
    unlink($tempFile);
});

it('createHttpClientOption with debug throws RuntimeException for bad path', function () {
    $this->config->setDebug(true);
    $this->config->setDebugFile('/root/forbidden/debug.log');

    $this->mock->append(new Response(200, [], json_encode([])));
    $this->api->getProfileWithHttpInfo('token');
})->throws(RuntimeException::class);

it('createHttpClientOption sets cert and ssl_key', function () {
    $this->config->setCertFile('/fake/cert.pem');
    $this->config->setKeyFile('/fake/key.pem');

    $this->mock->append(new Response(200, [], json_encode([
        'CompanyName' => 'Cert Co',
        'ActualFormat' => 'json',
        'ActualLanguage' => 'hu',
    ])));

    $result = $this->api->getProfileWithHttpInfo('token');

    expect($result[1])->toBe(200);
});

it('handleResponseWithDataType throws ApiException on invalid JSON for non-200 2xx', function () {
    $this->mock->append(new Response(204, ['Content-Type' => 'application/json'], 'not-valid-json{{{'));
    $this->api->getProfileWithHttpInfo('token');
})->throws(ApiException::class, 'Error JSON decoding server response');
