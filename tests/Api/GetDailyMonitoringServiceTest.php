<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Omisai\CreditOnline\Api\GetDailyMonitoringService;
use Omisai\CreditOnline\ApiException;
use Omisai\CreditOnline\Configuration;
use Omisai\CreditOnline\HeaderSelector;
use Omisai\CreditOnline\Model\Event;

beforeEach(function () {
    $this->mock = new MockHandler;
    $this->handlerStack = HandlerStack::create($this->mock);
    $this->client = new Client(['handler' => $this->handlerStack]);
    $this->config = new Configuration;
    $this->api = new GetDailyMonitoringService($this->client, $this->config);
    $this->date = new DateTime('2024-01-15');
});

it('can inject custom Guzzle ClientInterface', function () {
    $mock = new MockHandler([
        new Response(200, [], json_encode([])),
    ]);
    $client = new Client(['handler' => HandlerStack::create($mock)]);
    $api = new GetDailyMonitoringService($client, $this->config);

    $result = $api->dailyMonitoringGet('test-token', $this->date);

    expect($result)->toBeArray();
});

it('can inject custom Configuration', function () {
    $config = new Configuration;
    $config->setHost('https://custom.example.com/v2');

    $api = new GetDailyMonitoringService(null, $config);

    expect($api->getConfig()->getHost())->toBe('https://custom.example.com/v2');
});

it('can inject custom HeaderSelector', function () {
    $selector = new HeaderSelector;
    $api = new GetDailyMonitoringService(null, null, $selector);

    $request = $api->dailyMonitoringGetRequest('token', $this->date);

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
    expect(defined(GetDailyMonitoringService::class.'::contentTypes'))->toBeTrue();
});

it('contentTypes has dailyMonitoringGet with application/json', function () {
    expect(GetDailyMonitoringService::contentTypes)->toBeArray()
        ->toHaveKey('dailyMonitoringGet');
    expect(GetDailyMonitoringService::contentTypes['dailyMonitoringGet'])->toBe(['application/json']);
});

it('dailyMonitoringGet returns Event array', function () {
    $this->mock->append(new Response(200, [], json_encode([
        [
            'Taxnumber' => '12345678-2-41',
            'Name' => 'Test Event Company',
            'Category' => 'General',
            'Link' => 'https://example.com/event',
        ],
    ])));

    $result = $this->api->dailyMonitoringGet('test-token', $this->date);

    expect($result)->toBeArray()->toHaveCount(1);
    expect($result[0])->toBeInstanceOf(Event::class);
    expect($result[0]->getTaxnumber())->toBe('12345678-2-41');
    expect($result[0]->getName())->toBe('Test Event Company');
    expect($result[0]->getCategory())->toBe('General');
    expect($result[0]->getLink())->toBe('https://example.com/event');
});

it('dailyMonitoringGet returns multiple Event objects', function () {
    $this->mock->append(new Response(200, [], json_encode([
        [
            'Taxnumber' => '11111111-2-41',
            'Name' => 'Alpha Kft.',
            'Category' => 'News',
            'Link' => 'https://example.com/alpha',
        ],
        [
            'Taxnumber' => '22222222-2-41',
            'Name' => 'Beta Zrt.',
            'Category' => 'Alert',
            'Link' => 'https://example.com/beta',
        ],
    ])));

    $result = $this->api->dailyMonitoringGet('test-token', $this->date);

    expect($result)->toBeArray()->toHaveCount(2);
    expect($result[0])->toBeInstanceOf(Event::class);
    expect($result[1])->toBeInstanceOf(Event::class);
    expect($result[0]->getName())->toBe('Alpha Kft.');
    expect($result[1]->getName())->toBe('Beta Zrt.');
});

it('dailyMonitoringGet returns empty array when no events', function () {
    $this->mock->append(new Response(200, [], json_encode([])));

    $result = $this->api->dailyMonitoringGet('test-token', $this->date);

    expect($result)->toBeArray()->toHaveCount(0);
});

it('dailyMonitoringGetWithHttpInfo returns array with status code', function () {
    $this->mock->append(new Response(200, ['X-Events-Count' => '3'], json_encode([
        [
            'Taxnumber' => '33333333-2-41',
            'Name' => 'Gamma Bt.',
            'Category' => 'Update',
            'Link' => 'https://example.com/gamma',
        ],
    ])));

    $result = $this->api->dailyMonitoringGetWithHttpInfo('test-token', $this->date);

    expect($result)->toBeArray()->toHaveCount(3);
    expect($result[0])->toBeArray()->toHaveCount(1);
    expect($result[0][0])->toBeInstanceOf(Event::class);
    expect($result[1])->toBe(200);
    expect($result[2])->toBeArray();
    expect($result[2]['X-Events-Count'])->toBe(['3']);
});

it('dailyMonitoringGetAsync returns a Promise', function () {
    $this->mock->append(new Response(200, [], json_encode([])));

    $promise = $this->api->dailyMonitoringGetAsync('test-token', $this->date);

    expect($promise)->toBeInstanceOf(PromiseInterface::class);
});

it('dailyMonitoringGetAsync resolves to Event array', function () {
    $this->mock->append(new Response(200, [], json_encode([
        [
            'Taxnumber' => '44444444-2-41',
            'Name' => 'Delta Kft.',
            'Category' => 'Info',
            'Link' => 'https://example.com/delta',
        ],
    ])));

    $result = $this->api->dailyMonitoringGetAsync('test-token', $this->date)->wait();

    expect($result)->toBeArray()->toHaveCount(1);
    expect($result[0])->toBeInstanceOf(Event::class);
});

it('dailyMonitoringGetAsyncWithHttpInfo returns a Promise', function () {
    $this->mock->append(new Response(200, [], json_encode([])));

    $promise = $this->api->dailyMonitoringGetAsyncWithHttpInfo('test-token', $this->date);

    expect($promise)->toBeInstanceOf(PromiseInterface::class);
});

it('dailyMonitoringGetAsyncWithHttpInfo resolves with array', function () {
    $this->mock->append(new Response(200, ['X-Header' => 'val'], json_encode([
        [
            'Taxnumber' => '55555555-2-41',
            'Name' => 'Epsilon Kft.',
            'Category' => 'Warning',
            'Link' => 'https://example.com/epsilon',
        ],
    ])));

    $result = $this->api->dailyMonitoringGetAsyncWithHttpInfo('test-token', $this->date)->wait();

    expect($result)->toBeArray()->toHaveCount(3);
    expect($result[0])->toBeArray()->toHaveCount(1);
    expect($result[0][0])->toBeInstanceOf(Event::class);
    expect($result[1])->toBe(200);
    expect($result[2])->toBeArray();
});

it('dailyMonitoringGetRequest creates GET request to /DailyMonitoring', function () {
    $request = $this->api->dailyMonitoringGetRequest('my-token', $this->date);

    expect($request->getMethod())->toBe('GET');
    expect($request->getUri()->getPath())->toBe('/v3/DailyMonitoring');
    expect($request->getUri()->getHost())->toBe('api.creditonline.hu');
});

it('dailyMonitoringGetRequest includes token and date query parameters', function () {
    $request = $this->api->dailyMonitoringGetRequest('my-token', $this->date);

    $query = $request->getUri()->getQuery();
    expect($query)->toContain('token=my-token');
    expect($query)->toContain('date=');
});

it('dailyMonitoringGetRequest throws InvalidArgumentException when token is null', function () {
    $this->api->dailyMonitoringGetRequest(null, $this->date);
})->throws(InvalidArgumentException::class, 'Missing the required parameter $token when calling dailyMonitoringGet');

it('dailyMonitoringGetRequest throws InvalidArgumentException when date is null', function () {
    $this->api->dailyMonitoringGetRequest('token', null);
})->throws(InvalidArgumentException::class, 'Missing the required parameter $date when calling dailyMonitoringGet');

it('throws ApiException on 400 bad request', function () {
    $this->mock->append(new Response(400, [], json_encode(['error' => 'Bad Request'])));

    $this->api->dailyMonitoringGetWithHttpInfo('bad-token', $this->date);
})->throws(ApiException::class);

it('throws ApiException on 401 unauthorized', function () {
    $this->mock->append(new Response(401, [], json_encode(['error' => 'Unauthorized'])));

    $this->api->dailyMonitoringGetWithHttpInfo('invalid-token', $this->date);
})->throws(ApiException::class);

it('throws ApiException on connection failure', function () {
    $this->mock->append(new ConnectException('Connection refused', new Request('GET', 'test')));

    $this->api->dailyMonitoringGetWithHttpInfo('test-token', $this->date);
})->throws(ApiException::class);

it('constructor with hostIndex defaults to 0', function () {
    $api = new GetDailyMonitoringService(null, null, null, 0);

    expect($api->getHostIndex())->toBe(0);
});

it('constructor accepts custom hostIndex', function () {
    $api = new GetDailyMonitoringService(null, null, null, 1);

    expect($api->getHostIndex())->toBe(1);
});

it('dailyMonitoringGetRequest with custom contentType uses it in headers', function () {
    $request = $this->api->dailyMonitoringGetRequest('token', $this->date, 'application/xml');

    expect($request->getHeaderLine('Content-Type'))->toBe('application/xml');
});

it('dailyMonitoringGetRequest allows empty string token', function () {
    $request = $this->api->dailyMonitoringGetRequest('', $this->date);

    expect($request)->toBeInstanceOf(Request::class);
    expect($request->getUri()->getQuery())->toContain('token=');
});

it('dailyMonitoringGetRequest throws InvalidArgumentException when date is empty array', function () {
    $this->api->dailyMonitoringGetRequest('token', []);
})->throws(InvalidArgumentException::class, 'Missing the required parameter $date when calling dailyMonitoringGet');

it('dailyMonitoringGetWithHttpInfo handles 201 non-200 2xx response', function () {
    $this->mock->append(new Response(201, ['X-Created' => 'yes'], json_encode([
        [
            'Taxnumber' => '77777777-2-41',
            'Name' => 'Created Kft.',
            'Category' => 'New',
            'Link' => 'https://example.com/created',
        ],
    ])));

    $result = $this->api->dailyMonitoringGetWithHttpInfo('token', $this->date);

    expect($result[0])->toBeArray()->toHaveCount(1);
    expect($result[0][0])->toBeInstanceOf(Event::class);
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

    $this->api->dailyMonitoringGetWithHttpInfo('token', $this->date);
})->throws(ApiException::class);

it('ApiException from RequestException captures status code and body', function () {
    $this->mock->append(
        new RequestException(
            'Validation failed',
            new Request('GET', 'test'),
            new Response(422, ['X-Reason' => 'invalid'], json_encode(['field' => 'date']))
        )
    );

    try {
        $this->api->dailyMonitoringGetWithHttpInfo('token', $this->date);
    } catch (ApiException $e) {
        expect($e->getCode())->toBe(422);
        expect($e->getResponseBody())->toContain('date');
        expect($e->getResponseHeaders()['X-Reason'])->toBe(['invalid']);
    }
});

it('ApiException from ConnectException has null body and headers', function () {
    $this->mock->append(new ConnectException('Timeout', new Request('GET', 'test')));

    try {
        $this->api->dailyMonitoringGetWithHttpInfo('token', $this->date);
    } catch (ApiException $e) {
        expect($e->getCode())->toBe(0);
        expect($e->getResponseBody())->toBeNull();
        expect($e->getResponseHeaders())->toBeNull();
    }
});

it('dailyMonitoringGetAsyncWithHttpInfo rejects with ApiException on RequestException', function () {
    $this->mock->append(
        new RequestException(
            'Async failure',
            new Request('GET', 'test'),
            new Response(503, [], json_encode(['error' => 'Down']))
        )
    );

    $promise = $this->api->dailyMonitoringGetAsyncWithHttpInfo('token', $this->date);
    expect(fn () => $promise->wait())->toThrow(ApiException::class);
});

it('createHttpClientOption enables debug logging', function () {
    $tempFile = sys_get_temp_dir().'/creditonline-monitoring-debug-'.uniqid().'.log';
    $this->config->setDebug(true);
    $this->config->setDebugFile($tempFile);

    $this->mock->append(new Response(200, [], json_encode([])));

    $this->api->dailyMonitoringGetWithHttpInfo('token', $this->date);

    expect(file_exists($tempFile))->toBeTrue();
    unlink($tempFile);
});

it('createHttpClientOption with debug throws RuntimeException for bad path', function () {
    $this->config->setDebug(true);
    $this->config->setDebugFile('/root/forbidden/debug.log');

    $this->mock->append(new Response(200, [], json_encode([])));
    $this->api->dailyMonitoringGetWithHttpInfo('token', $this->date);
})->throws(RuntimeException::class);

it('createHttpClientOption sets cert and ssl_key', function () {
    $this->config->setCertFile('/fake/cert.pem');
    $this->config->setKeyFile('/fake/key.pem');

    $this->mock->append(new Response(200, [], json_encode([])));

    $result = $this->api->dailyMonitoringGetWithHttpInfo('token', $this->date);

    expect($result[1])->toBe(200);
});

it('handleResponseWithDataType throws ApiException on invalid JSON for non-200 2xx', function () {
    $this->mock->append(new Response(204, ['Content-Type' => 'application/json'], 'not-valid-json{{{'));
    $this->api->dailyMonitoringGetWithHttpInfo('token', $this->date);
})->throws(ApiException::class, 'Error JSON decoding server response');
