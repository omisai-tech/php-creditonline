<?php

use Omisai\CreditOnline\Configuration;

// ---- Constants ----

it('has BOOLEAN_FORMAT_INT constant', function () {
    expect(Configuration::BOOLEAN_FORMAT_INT)->toBe('int');
});

it('has BOOLEAN_FORMAT_STRING constant', function () {
    expect(Configuration::BOOLEAN_FORMAT_STRING)->toBe('string');
});

// ---- Default host ----

it('has the correct default host', function () {
    $config = new Configuration();

    expect($config->getHost())->toBe('https://api.creditonline.hu/v3');
});

// ---- setHost / getHost ----

it('sets and gets the host', function () {
    $config = new Configuration();

    $config->setHost('https://custom.example.com/v1');

    expect($config->getHost())->toBe('https://custom.example.com/v1');
});

it('setHost returns the instance for chaining', function () {
    $config = new Configuration();

    $result = $config->setHost('https://example.com');

    expect($result)->toBe($config);
});

// ---- setTestHost ----

it('setTestHost sets the host to the test host value', function () {
    $config = new Configuration();

    $config->setTestHost('https://api-test.creditonline.hu/v3');

    expect($config->getHost())->toBe('https://api-test.creditonline.hu/v3');
});

it('setTestHost with null keeps default test host', function () {
    $config = new Configuration();
    // Manually set a custom host first
    $config->setHost('https://custom.example.com');

    $config->setTestHost(null);

    // When null is passed, it falls back to ->testHost property value
    expect($config->getHost())->toBe('https://api-test.creditonline.hu/v3');
});

// ---- setApiKey / getApiKey / getApiKeyWithPrefix ----

it('sets and gets an API key', function () {
    $config = new Configuration();

    $config->setApiKey('api_token', 'secret-token-123');

    expect($config->getApiKey('api_token'))->toBe('secret-token-123');
});

it('getApiKey returns null for unknown identifier', function () {
    $config = new Configuration();

    expect($config->getApiKey('nonexistent'))->toBeNull();
});

it('returns chained instance from setApiKey', function () {
    $config = new Configuration();

    $result = $config->setApiKey('key', 'val');

    expect($result)->toBe($config);
});

it('getApiKeyWithPrefix returns api key with prefix', function () {
    $config = new Configuration();
    $config->setApiKey('token', 'abc123');
    $config->setApiKeyPrefix('token', 'Bearer');

    expect($config->getApiKeyWithPrefix('token'))->toBe('Bearer abc123');
});

it('getApiKeyWithPrefix returns api key without prefix when prefix is null', function () {
    $config = new Configuration();
    $config->setApiKey('token', 'abc123');

    expect($config->getApiKeyWithPrefix('token'))->toBe('abc123');
});

it('getApiKeyWithPrefix returns null when api key is not set', function () {
    $config = new Configuration();

    expect($config->getApiKeyWithPrefix('nonexistent'))->toBeNull();
});

// ---- setApiKeyPrefix / getApiKeyPrefix ----

it('sets and gets API key prefix', function () {
    $config = new Configuration();

    $config->setApiKeyPrefix('token', 'Bearer');

    expect($config->getApiKeyPrefix('token'))->toBe('Bearer');
});

it('getApiKeyPrefix returns null for unknown identifier', function () {
    $config = new Configuration();

    expect($config->getApiKeyPrefix('nonexistent'))->toBeNull();
});

it('setApiKeyPrefix returns chained instance', function () {
    $config = new Configuration();

    $result = $config->setApiKeyPrefix('token', 'Basic');

    expect($result)->toBe($config);
});

// ---- setAccessToken / getAccessToken ----

it('sets and gets the access token', function () {
    $config = new Configuration();

    $config->setAccessToken('oauth-token-xyz');

    expect($config->getAccessToken())->toBe('oauth-token-xyz');
});

it('setAccessToken returns chained instance', function () {
    $config = new Configuration();

    $result = $config->setAccessToken('token');

    expect($result)->toBe($config);
});

it('default access token is empty string', function () {
    $config = new Configuration();

    expect($config->getAccessToken())->toBe('');
});

// ---- setUsername / getUsername ----

it('sets and gets the username', function () {
    $config = new Configuration();

    $config->setUsername('john.doe');

    expect($config->getUsername())->toBe('john.doe');
});

it('setUsername returns chained instance', function () {
    $config = new Configuration();

    $result = $config->setUsername('john');

    expect($result)->toBe($config);
});

it('default username is empty string', function () {
    $config = new Configuration();

    expect($config->getUsername())->toBe('');
});

// ---- setPassword / getPassword ----

it('sets and gets the password', function () {
    $config = new Configuration();

    $config->setPassword('s3cr3t');

    expect($config->getPassword())->toBe('s3cr3t');
});

it('setPassword returns chained instance', function () {
    $config = new Configuration();

    $result = $config->setPassword('pass');

    expect($result)->toBe($config);
});

it('default password is empty string', function () {
    $config = new Configuration();

    expect($config->getPassword())->toBe('');
});

// ---- setUserAgent / getUserAgent ----

it('sets and gets the user agent', function () {
    $config = new Configuration();

    $config->setUserAgent('MyApp/2.0');

    expect($config->getUserAgent())->toBe('MyApp/2.0');
});

it('setUserAgent returns chained instance', function () {
    $config = new Configuration();

    $result = $config->setUserAgent('Agent');

    expect($result)->toBe($config);
});

it('default user agent is the OpenAPI generator default', function () {
    $config = new Configuration();

    expect($config->getUserAgent())->toBe('OpenAPI-Generator/1.0.0/PHP');
});

it('setUserAgent throws InvalidArgumentException when value is not a string', function ($value) {
    $config = new Configuration();
    $config->setUserAgent($value);
})->with([
    'int 123' => 123,
    'float 45.67' => 45.67,
    'bool true' => true,
    'bool false' => false,
    'stdClass' => fn () => new stdClass(),
    'null' => null,
])->throws(\InvalidArgumentException::class, 'User-agent must be a string.');

// ---- setDebug / getDebug ----

it('sets and gets the debug flag', function () {
    $config = new Configuration();

    $config->setDebug(true);

    expect($config->getDebug())->toBeTrue();
});

it('default debug flag is false', function () {
    $config = new Configuration();

    expect($config->getDebug())->toBeFalse();
});

it('setDebug returns chained instance', function () {
    $config = new Configuration();

    $result = $config->setDebug(true);

    expect($result)->toBe($config);
});

// ---- setDebugFile / getDebugFile ----

it('sets and gets the debug file', function () {
    $config = new Configuration();

    $config->setDebugFile('/tmp/debug.log');

    expect($config->getDebugFile())->toBe('/tmp/debug.log');
});

it('default debug file is php output stream', function () {
    $config = new Configuration();

    expect($config->getDebugFile())->toBe('php://output');
});

it('setDebugFile returns chained instance', function () {
    $config = new Configuration();

    $result = $config->setDebugFile('/tmp/log');

    expect($result)->toBe($config);
});

// ---- setTempFolderPath / getTempFolderPath ----

it('sets and gets the temp folder path', function () {
    $config = new Configuration();

    $config->setTempFolderPath('/custom/tmp');

    expect($config->getTempFolderPath())->toBe('/custom/tmp');
});

it('default temp folder path is system temp dir', function () {
    $config = new Configuration();

    expect($config->getTempFolderPath())->toBe(sys_get_temp_dir());
});

it('setTempFolderPath returns chained instance', function () {
    $config = new Configuration();

    $result = $config->setTempFolderPath('/tmp');

    expect($result)->toBe($config);
});

// ---- setCertFile / getCertFile (mTLS) ----

it('sets and gets the certificate file path for mTLS', function () {
    $config = new Configuration();

    $config->setCertFile('/path/to/cert.pem');

    expect($config->getCertFile())->toBe('/path/to/cert.pem');
});

it('default cert file is null', function () {
    $config = new Configuration();

    expect($config->getCertFile())->toBeNull();
});

it('setCertFile returns chained instance', function () {
    $config = new Configuration();

    $result = $config->setCertFile('/path/cert.pem');

    expect($result)->toBe($config);
});

// ---- setKeyFile / getKeyFile (mTLS) ----

it('sets and gets the key file path for mTLS', function () {
    $config = new Configuration();

    $config->setKeyFile('/path/to/key.pem');

    expect($config->getKeyFile())->toBe('/path/to/key.pem');
});

it('default key file is null', function () {
    $config = new Configuration();

    expect($config->getKeyFile())->toBeNull();
});

it('setKeyFile returns chained instance', function () {
    $config = new Configuration();

    $result = $config->setKeyFile('/path/key.pem');

    expect($result)->toBe($config);
});

// ---- setBooleanFormatForQueryString / getBooleanFormatForQueryString ----

it('sets and gets boolean format for query string', function () {
    $config = new Configuration();

    $config->setBooleanFormatForQueryString(Configuration::BOOLEAN_FORMAT_STRING);

    expect($config->getBooleanFormatForQueryString())->toBe('string');
});

it('default boolean format is int', function () {
    $config = new Configuration();

    expect($config->getBooleanFormatForQueryString())->toBe('int');
});

it('setBooleanFormatForQueryString returns chained instance', function () {
    $config = new Configuration();

    $result = $config->setBooleanFormatForQueryString(Configuration::BOOLEAN_FORMAT_INT);

    expect($result)->toBe($config);
});

// ---- getDefaultConfiguration / setDefaultConfiguration (singleton) ----

it('getDefaultConfiguration returns a singleton instance', function () {
    $config1 = Configuration::getDefaultConfiguration();
    $config2 = Configuration::getDefaultConfiguration();

    expect($config1)->toBe($config2);
    expect($config1)->toBeInstanceOf(Configuration::class);
});

it('setDefaultConfiguration overrides the singleton', function () {
    $original = Configuration::getDefaultConfiguration();
    $custom = new Configuration();
    $custom->setHost('https://my-custom.example.com');

    Configuration::setDefaultConfiguration($custom);

    $retrieved = Configuration::getDefaultConfiguration();

    expect($retrieved)->toBe($custom);
    expect($retrieved->getHost())->toBe('https://my-custom.example.com');

    // Restore original
    Configuration::setDefaultConfiguration($original);
});

// ---- toDebugReport ----

it('toDebugReport returns a string containing debug information', function () {
    $report = Configuration::toDebugReport();

    expect($report)->toBeString()
        ->toContain('PHP SDK (OmisaiCreditOnline) Debug Report:')
        ->toContain('OS:')
        ->toContain('PHP Version:')
        ->toContain('The version of the OpenAPI document: 3')
        ->toContain('Temp Folder Path:');
});

// ---- getHostSettings ----

it('getHostSettings returns array with production and test hosts', function () {
    $config = new Configuration();
    $settings = $config->getHostSettings();

    expect($settings)->toBeArray()->toHaveCount(2);
    expect($settings[0]['url'])->toBe('https://api.creditonline.hu/v3');
    expect($settings[0]['description'])->toBe('No description provided');
    expect($settings[1]['url'])->toBe('https://api-test.creditonline.hu/v3');
    expect($settings[1]['description'])->toBe('No description provided');
});

// ---- getHostFromSettings ----

it('getHostFromSettings returns the production host URL for index 0', function () {
    $config = new Configuration();

    expect($config->getHostFromSettings(0))->toBe('https://api.creditonline.hu/v3');
});

it('getHostFromSettings returns the test host URL for index 1', function () {
    $config = new Configuration();

    expect($config->getHostFromSettings(1))->toBe('https://api-test.creditonline.hu/v3');
});

// ---- getHostString (static method) ----

it('getHostString returns correct URL for a given host index', function () {
    $hostSettings = [
        ['url' => 'https://api.example.com/v1', 'description' => ''],
        ['url' => 'https://api-test.example.com/v1', 'description' => ''],
    ];

    expect(Configuration::getHostString($hostSettings, 0))->toBe('https://api.example.com/v1');
    expect(Configuration::getHostString($hostSettings, 1))->toBe('https://api-test.example.com/v1');
});

it('getHostString throws InvalidArgumentException on invalid host index', function () {
    $hostSettings = [['url' => 'https://api.example.com/v1']];

    Configuration::getHostString($hostSettings, 5);
})->throws(\InvalidArgumentException::class, 'Invalid index 5 when selecting the host. Must be less than 1');

it('getHostString throws InvalidArgumentException on negative host index', function () {
    $hostSettings = [['url' => 'https://api.example.com/v1']];

    Configuration::getHostString($hostSettings, -1);
})->throws(\InvalidArgumentException::class);

it('getHostString substitutes variables in the URL', function () {
    $hostSettings = [
        [
            'url' => 'https://{region}.api.example.com/{version}',
            'description' => '',
            'variables' => [
                'region' => [
                    'default_value' => 'us-east',
                    'enum_values' => ['us-east', 'eu-west'],
                ],
                'version' => [
                    'default_value' => 'v1',
                    'enum_values' => ['v1', 'v2'],
                ],
            ],
        ],
    ];

    $result = Configuration::getHostString($hostSettings, 0, ['region' => 'eu-west', 'version' => 'v2']);

    expect($result)->toBe('https://eu-west.api.example.com/v2');
});

it('getHostString uses default variable values when not provided', function () {
    $hostSettings = [
        [
            'url' => 'https://{region}.api.example.com/{version}',
            'description' => '',
            'variables' => [
                'region' => [
                    'default_value' => 'us-east',
                    'enum_values' => ['us-east', 'eu-west'],
                ],
                'version' => [
                    'default_value' => 'v1',
                    'enum_values' => ['v1', 'v2'],
                ],
            ],
        ],
    ];

    $result = Configuration::getHostString($hostSettings, 0, ['region' => 'eu-west']);

    expect($result)->toBe('https://eu-west.api.example.com/v1');
});

it('getHostString throws InvalidArgumentException when variable value is not in enum', function () {
    $hostSettings = [
        [
            'url' => 'https://{region}.api.example.com',
            'description' => '',
            'variables' => [
                'region' => [
                    'default_value' => 'us-east',
                    'enum_values' => ['us-east', 'eu-west'],
                ],
            ],
        ],
    ];

    Configuration::getHostString($hostSettings, 0, ['region' => 'invalid-region']);
})->throws(\InvalidArgumentException::class);

it('getHostString handles host settings with no variables', function () {
    $hostSettings = [
        ['url' => 'https://api.example.com/v1', 'description' => ''],
    ];

    $result = Configuration::getHostString($hostSettings, 0, ['unused' => 'value']);

    expect($result)->toBe('https://api.example.com/v1');
});

it('getHostString handles null variables parameter', function () {
    $hostSettings = [
        [
            'url' => 'https://{region}.api.example.com',
            'description' => '',
            'variables' => [
                'region' => [
                    'default_value' => 'us-east',
                    'enum_values' => ['us-east', 'eu-west'],
                ],
            ],
        ],
    ];

    $result = Configuration::getHostString($hostSettings, 0, null);

    expect($result)->toBe('https://us-east.api.example.com');
});
