<?php

use Omisai\CreditOnline\Configuration;

// ---------------------------------------------------------------------------
// State management
// ---------------------------------------------------------------------------

beforeEach(function () {
    $this->config = new Configuration;
});

$originalDefaultConfig = Configuration::getDefaultConfiguration();

afterEach(function () use ($originalDefaultConfig) {
    Configuration::setDefaultConfiguration($originalDefaultConfig);
});

// ---------------------------------------------------------------------------
// Constants
// ---------------------------------------------------------------------------

it('has BOOLEAN_FORMAT_INT constant', function () {
    expect(Configuration::BOOLEAN_FORMAT_INT)->toBe('int');
});

it('has BOOLEAN_FORMAT_STRING constant', function () {
    expect(Configuration::BOOLEAN_FORMAT_STRING)->toBe('string');
});

// ---------------------------------------------------------------------------
// Default host
// ---------------------------------------------------------------------------

it('has default host set to production URL', function () {
    expect($this->config->getHost())->toBe('https://api.creditonline.hu/v3');
});

// ---------------------------------------------------------------------------
// Host set/get
// ---------------------------------------------------------------------------

it('sets and gets host', function () {
    $this->config->setHost('https://custom.example.com/api');
    expect($this->config->getHost())->toBe('https://custom.example.com/api');
});

// ---------------------------------------------------------------------------
// API key set/get
// ---------------------------------------------------------------------------

it('sets and gets API key', function () {
    $this->config->setApiKey('X-Api-Key', 'secret123');
    expect($this->config->getApiKey('X-Api-Key'))->toBe('secret123');
});

it('returns null for unset API key', function () {
    expect($this->config->getApiKey('nonexistent'))->toBeNull();
});

// ---------------------------------------------------------------------------
// API key prefix set/get
// ---------------------------------------------------------------------------

it('sets and gets API key prefix', function () {
    $this->config->setApiKeyPrefix('X-Api-Key', 'Bearer');
    expect($this->config->getApiKeyPrefix('X-Api-Key'))->toBe('Bearer');
});

it('returns null for unset API key prefix', function () {
    expect($this->config->getApiKeyPrefix('nonexistent'))->toBeNull();
});

// ---------------------------------------------------------------------------
// API key with prefix
// ---------------------------------------------------------------------------

it('returns API key with prefix when both are set', function () {
    $this->config->setApiKey('Auth', 'abcdef');
    $this->config->setApiKeyPrefix('Auth', 'Bearer');
    expect($this->config->getApiKeyWithPrefix('Auth'))->toBe('Bearer abcdef');
});

it('returns API key only when prefix is null', function () {
    $this->config->setApiKey('Auth', 'abcdef');
    expect($this->config->getApiKeyWithPrefix('Auth'))->toBe('abcdef');
});

it('returns null for unset API key with prefix', function () {
    expect($this->config->getApiKeyWithPrefix('missing'))->toBeNull();
});

// ---------------------------------------------------------------------------
// Access token
// ---------------------------------------------------------------------------

it('sets and gets access token', function () {
    $this->config->setAccessToken('oauth-token-123');
    expect($this->config->getAccessToken())->toBe('oauth-token-123');
});

it('has empty string as default access token', function () {
    expect($this->config->getAccessToken())->toBe('');
});

// ---------------------------------------------------------------------------
// Username
// ---------------------------------------------------------------------------

it('sets and gets username', function () {
    $this->config->setUsername('johndoe');
    expect($this->config->getUsername())->toBe('johndoe');
});

it('has empty string as default username', function () {
    expect($this->config->getUsername())->toBe('');
});

// ---------------------------------------------------------------------------
// Password
// ---------------------------------------------------------------------------

it('sets and gets password', function () {
    $this->config->setPassword('s3cret!');
    expect($this->config->getPassword())->toBe('s3cret!');
});

it('has empty string as default password', function () {
    expect($this->config->getPassword())->toBe('');
});

// ---------------------------------------------------------------------------
// User agent
// ---------------------------------------------------------------------------

it('sets and gets user agent', function () {
    $this->config->setUserAgent('MyApp/2.0');
    expect($this->config->getUserAgent())->toBe('MyApp/2.0');
});

it('has default user agent', function () {
    expect($this->config->getUserAgent())->toBe('OpenAPI-Generator/1.0.0/PHP');
});

it('throws InvalidArgumentException for non-string user agent', function ($value) {
    $this->config->setUserAgent($value);
})->throws(InvalidArgumentException::class, 'User-agent must be a string.')->with([
    'int' => 123,
    'float' => 45.67,
    'true' => true,
    'false' => false,
    'stdClass' => fn () => new stdClass,
    'null' => null,
]);

// ---------------------------------------------------------------------------
// Debug
// ---------------------------------------------------------------------------

it('sets and gets debug', function () {
    expect($this->config->getDebug())->toBeFalse();
    $this->config->setDebug(true);
    expect($this->config->getDebug())->toBeTrue();
});

// ---------------------------------------------------------------------------
// Debug file
// ---------------------------------------------------------------------------

it('sets and gets debug file', function () {
    $this->config->setDebugFile('/tmp/debug.log');
    expect($this->config->getDebugFile())->toBe('/tmp/debug.log');
});

it('has default debug file as php://output', function () {
    expect($this->config->getDebugFile())->toBe('php://output');
});

// ---------------------------------------------------------------------------
// Temp folder path
// ---------------------------------------------------------------------------

it('sets and gets temp folder path', function () {
    $this->config->setTempFolderPath('/custom/tmp');
    expect($this->config->getTempFolderPath())->toBe('/custom/tmp');
});

it('has sys_get_temp_dir as default temp folder path', function () {
    expect($this->config->getTempFolderPath())->toBe(sys_get_temp_dir());
});

// ---------------------------------------------------------------------------
// Cert file
// ---------------------------------------------------------------------------

it('sets and gets cert file', function () {
    $this->config->setCertFile('/path/to/cert.pem');
    expect($this->config->getCertFile())->toBe('/path/to/cert.pem');
});

it('has null as default cert file', function () {
    expect($this->config->getCertFile())->toBeNull();
});

// ---------------------------------------------------------------------------
// Key file
// ---------------------------------------------------------------------------

it('sets and gets key file', function () {
    $this->config->setKeyFile('/path/to/key.pem');
    expect($this->config->getKeyFile())->toBe('/path/to/key.pem');
});

it('has null as default key file', function () {
    expect($this->config->getKeyFile())->toBeNull();
});

// ---------------------------------------------------------------------------
// Boolean format for query string
// ---------------------------------------------------------------------------

it('sets and gets boolean format for query string', function () {
    expect($this->config->getBooleanFormatForQueryString())->toBe(Configuration::BOOLEAN_FORMAT_INT);
    $this->config->setBooleanFormatForQueryString(Configuration::BOOLEAN_FORMAT_STRING);
    expect($this->config->getBooleanFormatForQueryString())->toBe(Configuration::BOOLEAN_FORMAT_STRING);
});

// ---------------------------------------------------------------------------
// setTestHost
// ---------------------------------------------------------------------------

it('setTestHost with a value sets the host to that value', function () {
    $this->config->setTestHost('https://staging.example.com');
    expect($this->config->getHost())->toBe('https://staging.example.com');
});

it('setTestHost with null falls back to test host property', function () {
    $this->config->setTestHost(null);
    expect($this->config->getHost())->toBe('https://api-test.creditonline.hu/v3');
});

// ---------------------------------------------------------------------------
// getDefaultConfiguration / setDefaultConfiguration
// ---------------------------------------------------------------------------

it('getDefaultConfiguration returns a Configuration instance', function () {
    expect(Configuration::getDefaultConfiguration())->toBeInstanceOf(Configuration::class);
});

it('getDefaultConfiguration returns singleton', function () {
    $a = Configuration::getDefaultConfiguration();
    $b = Configuration::getDefaultConfiguration();
    expect($a)->toBe($b);
});

it('setDefaultConfiguration overrides the singleton', function () {
    $newConfig = new Configuration;
    $newConfig->setHost('https://overridden.example.com');
    Configuration::setDefaultConfiguration($newConfig);

    expect(Configuration::getDefaultConfiguration()->getHost())->toBe('https://overridden.example.com');
    expect(Configuration::getDefaultConfiguration())->toBe($newConfig);
});

// ---------------------------------------------------------------------------
// toDebugReport
// ---------------------------------------------------------------------------

it('toDebugReport returns a string', function () {
    $report = Configuration::toDebugReport();
    expect($report)->toBeString();
});

it('toDebugReport contains expected sections', function () {
    $report = Configuration::toDebugReport();
    expect($report)->toContain('PHP SDK (Omisai\CreditOnline) Debug Report:');
    expect($report)->toContain('OS:');
    expect($report)->toContain('PHP Version:');
    expect($report)->toContain('Temp Folder Path:');
});

// ---------------------------------------------------------------------------
// getHostSettings
// ---------------------------------------------------------------------------

it('getHostSettings returns array with 2 hosts', function () {
    $settings = $this->config->getHostSettings();
    expect($settings)->toBeArray()->toHaveCount(2);
});

it('getHostSettings first host is production URL', function () {
    $settings = $this->config->getHostSettings();
    expect($settings[0]['url'])->toBe('https://api.creditonline.hu/v3');
});

it('getHostSettings second host is test URL', function () {
    $settings = $this->config->getHostSettings();
    expect($settings[1]['url'])->toBe('https://api-test.creditonline.hu/v3');
});

// ---------------------------------------------------------------------------
// getHostFromSettings
// ---------------------------------------------------------------------------

it('getHostFromSettings returns production URL for index 0', function () {
    expect($this->config->getHostFromSettings(0))->toBe('https://api.creditonline.hu/v3');
});

it('getHostFromSettings returns test URL for index 1', function () {
    expect($this->config->getHostFromSettings(1))->toBe('https://api-test.creditonline.hu/v3');
});

it('getHostFromSettings with null variables returns default URL', function () {
    expect($this->config->getHostFromSettings(0, null))->toBe('https://api.creditonline.hu/v3');
});

// ---------------------------------------------------------------------------
// getHostString — variable substitution
// ---------------------------------------------------------------------------

it('getHostString substitutes variables in URL', function () {
    $hostSettings = [
        [
            'url' => 'https://{region}.api.example.com/{version}',
            'description' => 'Regional',
            'variables' => [
                'region' => ['default_value' => 'eu', 'enum_values' => ['eu', 'us', 'ap']],
                'version' => ['default_value' => 'v1', 'enum_values' => ['v1', 'v2']],
            ],
        ],
    ];

    $url = Configuration::getHostString($hostSettings, 0, ['region' => 'us', 'version' => 'v2']);
    expect($url)->toBe('https://us.api.example.com/v2');
});

it('getHostString uses default values when variables not provided', function () {
    $hostSettings = [
        [
            'url' => 'https://api.example.com/{version}',
            'description' => 'Versioned',
            'variables' => [
                'version' => ['default_value' => 'v1', 'enum_values' => ['v1', 'v2']],
            ],
        ],
    ];

    $url = Configuration::getHostString($hostSettings, 0);
    expect($url)->toBe('https://api.example.com/v1');
});

it('getHostString uses provided variable value over default', function () {
    $hostSettings = [
        [
            'url' => 'https://api.example.com/{version}',
            'description' => 'Versioned',
            'variables' => [
                'version' => ['default_value' => 'v1', 'enum_values' => ['v1', 'v2']],
            ],
        ],
    ];

    $url = Configuration::getHostString($hostSettings, 0, ['version' => 'v2']);
    expect($url)->toBe('https://api.example.com/v2');
});

it('getHostString works without variables section', function () {
    $hostSettings = [
        [
            'url' => 'https://fixed.example.com',
            'description' => 'Static',
        ],
    ];

    $url = Configuration::getHostString($hostSettings, 0);
    expect($url)->toBe('https://fixed.example.com');
});

// ---------------------------------------------------------------------------
// getHostString — enum validation
// ---------------------------------------------------------------------------

it('getHostString throws for invalid enum value', function () {
    $hostSettings = [
        [
            'url' => 'https://{region}.example.com',
            'description' => 'Regional',
            'variables' => [
                'region' => ['default_value' => 'eu', 'enum_values' => ['eu', 'us']],
            ],
        ],
    ];

    Configuration::getHostString($hostSettings, 0, ['region' => 'invalid']);
})->throws(InvalidArgumentException::class);

// ---------------------------------------------------------------------------
// getHostString — invalid index
// ---------------------------------------------------------------------------

it('getHostString throws for negative index', function () {
    Configuration::getHostString($this->config->getHostSettings(), -1);
})->throws(InvalidArgumentException::class);

it('getHostString throws for out of bounds index', function () {
    Configuration::getHostString($this->config->getHostSettings(), 999);
})->throws(InvalidArgumentException::class);

// ---------------------------------------------------------------------------
// Chained returns
// ---------------------------------------------------------------------------

it('setHost returns $this for chaining', function () {
    $result = $this->config->setHost('https://chained.example.com');
    expect($result)->toBe($this->config);
});
