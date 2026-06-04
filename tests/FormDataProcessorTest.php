<?php

use Omisai\CreditOnline\FormDataProcessor;
use Omisai\CreditOnline\Model\Address;
use Omisai\CreditOnline\Model\Company;

// ---- Constructor ----

it('has_file defaults to false', function () {
    $processor = new FormDataProcessor;

    expect($processor->has_file)->toBeFalse();
});

// ---- prepare() - basic value types ----

it('prepares simple string values unchanged', function () {
    $processor = new FormDataProcessor;
    $result = $processor->prepare(['name' => 'John']);

    expect($result)->toBe(['name' => 'John']);
});

it('prepares multiple string values', function () {
    $processor = new FormDataProcessor;
    $result = $processor->prepare([
        'first_name' => 'Jane',
        'last_name' => 'Doe',
    ]);

    expect($result)->toBe([
        'first_name' => 'Jane',
        'last_name' => 'Doe',
    ]);
});

it('prepares DateTime values as ISO8601 strings', function () {
    $processor = new FormDataProcessor;
    $date = new DateTime('2024-01-15T10:30:00+00:00');
    $result = $processor->prepare(['created_at' => $date]);

    expect($result['created_at'])->toBe('2024-01-15T10:30:00+00:00');
});

it('prepares boolean true to string "true"', function () {
    $processor = new FormDataProcessor;
    $result = $processor->prepare(['active' => true]);

    expect($result['active'])->toBe('true');
});

it('prepares boolean false to string "false"', function () {
    $processor = new FormDataProcessor;
    $result = $processor->prepare(['active' => false]);

    expect($result['active'])->toBe('false');
});

it('prepares integer values as strings', function () {
    $processor = new FormDataProcessor;
    $result = $processor->prepare(['count' => 42]);

    expect($result['count'])->toBe('42');
});

it('prepares zero as string "0"', function () {
    $processor = new FormDataProcessor;
    $result = $processor->prepare(['count' => 0]);

    expect($result['count'])->toBe('0');
});

it('prepares negative integer as string', function () {
    $processor = new FormDataProcessor;
    $result = $processor->prepare(['offset' => -5]);

    expect($result['offset'])->toBe('-5');
});

it('prepares float values as strings', function () {
    $processor = new FormDataProcessor;
    $result = $processor->prepare(['price' => 9.99]);

    expect($result['price'])->toBe('9.99');
});

it('skips a single null value', function () {
    $processor = new FormDataProcessor;
    $result = $processor->prepare(['email' => null]);

    expect($result)->toBe([]);
});

it('skips null values among valid values', function () {
    $processor = new FormDataProcessor;
    $result = $processor->prepare([
        'name' => 'John',
        'email' => null,
        'age' => 30,
    ]);

    expect($result)->toHaveKeys(['name', 'age']);
    expect($result)->not()->toHaveKey('email');
    expect($result)->toBe([
        'name' => 'John',
        'age' => '30',
    ]);
});

it('returns empty array when all values are null', function () {
    $processor = new FormDataProcessor;
    $result = $processor->prepare([
        'a' => null,
        'b' => null,
    ]);

    expect($result)->toBe([]);
});

it('prepares mixed values of various types', function () {
    $date = new DateTime('2024-06-01T00:00:00+00:00');
    $processor = new FormDataProcessor;
    $result = $processor->prepare([
        'name' => 'Jane',
        'active' => true,
        'inactive' => false,
        'count' => 5,
        'created' => $date,
        'skipped' => null,
        'price' => 12.50,
    ]);

    expect($result)->toBe([
        'name' => 'Jane',
        'active' => 'true',
        'inactive' => 'false',
        'count' => '5',
        'created' => '2024-06-01T00:00:00+00:00',
        'price' => '12.5',
    ]);
});

it('returns empty array for empty input', function () {
    $processor = new FormDataProcessor;
    $result = $processor->prepare([]);

    expect($result)->toBe([]);
});

// ---- prepare() - array values ----

it('recurses into nested arrays and converts leaf scalars', function () {
    $processor = new FormDataProcessor;
    $result = $processor->prepare([
        'contact' => [
            'name' => 'John',
            'active' => true,
            'count' => 3,
        ],
    ]);

    expect($result['contact'])->toBe([
        'name' => 'John',
        'active' => 'true',
        'count' => '3',
    ]);
});

it('recurses into deeply nested arrays', function () {
    $processor = new FormDataProcessor;
    $result = $processor->prepare([
        'data' => [
            'level1' => [
                'level2' => [
                    'value' => 42,
                ],
            ],
        ],
    ]);

    expect($result['data']['level1']['level2']['value'])->toBe('42');
});

it('recurses into list arrays', function () {
    $processor = new FormDataProcessor;
    $result = $processor->prepare([
        'tags' => ['php', 'api', true],
    ]);

    expect($result['tags'])->toBe(['php', 'api', 'true']);
});

// ---- prepare() - has_file flag ----

it('has_file remains false when preparing non-file scalar data', function () {
    $processor = new FormDataProcessor;
    $processor->prepare(['name' => 'John']);

    expect($processor->has_file)->toBeFalse();
});

it('has_file remains false when preparing arrays', function () {
    $processor = new FormDataProcessor;
    $processor->prepare(['items' => ['a', 'b']]);

    expect($processor->has_file)->toBeFalse();
});

it('has_file is true when a resource value is present', function () {
    $resource = fopen('php://memory', 'r');
    $processor = new FormDataProcessor;
    $processor->prepare(['file' => $resource]);

    expect($processor->has_file)->toBeTrue();

    fclose($resource);
});

it('has_file is true when an SplFileObject is present', function () {
    $path = tempnam(sys_get_temp_dir(), 'test_');
    file_put_contents($path, 'content');
    $file = new SplFileObject($path);
    $processor = new FormDataProcessor;
    $processor->prepare(['file' => $file]);

    expect($processor->has_file)->toBeTrue();

    unlink($path);
});

it('has_file resets to false on the next prepare call', function () {
    $processor = new FormDataProcessor;

    $resource = fopen('php://memory', 'r');
    $processor->prepare(['file' => $resource]);
    fclose($resource);

    expect($processor->has_file)->toBeTrue();

    $processor->prepare(['name' => 'John']);

    expect($processor->has_file)->toBeFalse();
});

it('has_file is true when resource is deeply nested in an array', function () {
    $resource = fopen('php://memory', 'r');
    $processor = new FormDataProcessor;
    $processor->prepare([
        'wrapper' => [
            'file' => $resource,
        ],
    ]);

    expect($processor->has_file)->toBeTrue();

    fclose($resource);
});

// ---- prepare() - ModelInterface ----

it('processes ModelInterface objects and extracts non-null properties', function () {
    $address = new Address([
        'city' => 'Budapest',
        'zip' => '1055',
    ]);
    $processor = new FormDataProcessor;
    $result = $processor->prepare(['address' => $address]);

    expect($result['address'])->toBe([
        'zip' => '1055',
        'city' => 'Budapest',
    ]);
});

it('processes ModelInterface skipping null properties', function () {
    $address = new Address([
        'city' => 'Budapest',
    ]);
    $processor = new FormDataProcessor;
    $result = $processor->prepare(['address' => $address]);

    expect($result['address'])->toHaveKey('city');
    expect($result['address'])->not()->toHaveKey('zip');
    expect($result['address'])->not()->toHaveKey('country_code');
    expect($result['address'])->not()->toHaveKey('street');
    expect($result['address'])->not()->toHaveKey('place_type');
    expect($result['address'])->not()->toHaveKey('house_number');
});

it('processes ModelInterface with all properties set', function () {
    $address = new Address([
        'country_code' => 'HU',
        'zip' => '1055',
        'city' => 'Budapest',
        'street' => 'Fő utca',
        'place_type' => 'utca',
        'house_number' => '1',
    ]);
    $processor = new FormDataProcessor;
    $result = $processor->prepare(['address' => $address]);

    expect($result['address'])->toBe([
        'country_code' => 'HU',
        'zip' => '1055',
        'city' => 'Budapest',
        'street' => 'Fő utca',
        'place_type' => 'utca',
        'house_number' => '1',
    ]);
});

it('processes ModelInterface with empty model returns empty array', function () {
    $address = new Address;
    $processor = new FormDataProcessor;
    $result = $processor->prepare(['address' => $address]);

    expect($result['address'])->toBe([]);
});

it('processes nested ModelInterface objects', function () {
    $address = new Address([
        'city' => 'Budapest',
        'zip' => '1055',
    ]);
    $company = new Company;
    $company->offsetSet('headquarter', $address);
    $company->offsetSet('name', 'ACME Kft.');

    $processor = new FormDataProcessor;
    $result = $processor->prepare(['company' => $company]);

    expect($result['company']['name'])->toBe('ACME Kft.');
    expect($result['company']['headquarter'])->toBe([
        'zip' => '1055',
        'city' => 'Budapest',
    ]);
});

it('processes ModelInterface with DateTime property', function () {
    $date = new DateTime('2020-03-15T12:00:00+00:00');
    $company = new Company;
    $company->offsetSet('name', 'Test Kft.');
    $company->offsetSet('foundation', $date);

    $processor = new FormDataProcessor;
    $result = $processor->prepare(['company' => $company]);

    expect($result['company']['name'])->toBe('Test Kft.');
    expect($result['company']['foundation'])->toBe('2020-03-15T12:00:00+00:00');
});

it('processes ModelInterface with integer property', function () {
    $company = new Company;
    $company->offsetSet('name', 'Test Kft.');
    $company->offsetSet('employees', 42);

    $processor = new FormDataProcessor;
    $result = $processor->prepare(['company' => $company]);

    expect($result['company']['employees'])->toBe('42');
});

// ---- prepare() - non-ModelInterface objects ----

it('iterates stdClass public properties and converts values', function () {
    $obj = new stdClass;
    $obj->name = 'John';
    $obj->active = true;
    $obj->count = 10;

    $processor = new FormDataProcessor;
    $result = $processor->prepare(['data' => $obj]);

    expect($result['data'])->toBe([
        'name' => 'John',
        'active' => 'true',
        'count' => '10',
    ]);
});

it('converts DateTime properties inside stdClass to ISO8601 strings', function () {
    $date = new DateTime('2024-12-25T08:00:00+00:00');
    $obj = new stdClass;
    $obj->title = 'Event';
    $obj->starts_at = $date;

    $processor = new FormDataProcessor;
    $result = $processor->prepare(['event' => $obj]);

    expect($result['event']['title'])->toBe('Event');
    expect($result['event']['starts_at'])->toBe('2024-12-25T08:00:00+00:00');
});

it('converts DateTimeInterface objects to string even when value is not wrapped in array', function () {
    $date = new DateTime('2024-05-01T14:30:00+00:00');
    $processor = new FormDataProcessor;
    $result = $processor->prepare(['timestamp' => $date]);

    expect($result['timestamp'])->toBe('2024-05-01T14:30:00+00:00');
});

it('handles stdClass with null property', function () {
    $obj = new stdClass;
    $obj->name = 'John';
    $obj->email = null;

    $processor = new FormDataProcessor;
    $result = $processor->prepare(['user' => $obj]);

    expect($result['user'])->toHaveKey('name');
    expect($result['user']['name'])->toBe('John');
});

// ---- flatten() static method ----

it('returns a flat array with scalar values converted to string', function () {
    $result = FormDataProcessor::flatten([
        'name' => 'John',
        'age' => 30,
    ]);

    expect($result)->toBe([
        'name' => 'John',
        'age' => '30',
    ]);
});

it('flattens nested associative array with bracket notation', function () {
    $result = FormDataProcessor::flatten([
        'user' => [
            'name' => 'Jane',
            'email' => 'jane@example.com',
        ],
    ]);

    expect($result)->toBe([
        'user[name]' => 'Jane',
        'user[email]' => 'jane@example.com',
    ]);
});

it('flattens nested list array with index bracket notation', function () {
    $result = FormDataProcessor::flatten([
        'items' => ['apple', 'banana', 'cherry'],
    ]);

    expect($result)->toBe([
        'items[0]' => 'apple',
        'items[1]' => 'banana',
        'items[2]' => 'cherry',
    ]);
});

it('flatten returns empty array for empty input', function () {
    $result = FormDataProcessor::flatten([]);

    expect($result)->toBe([]);
});

it('handles deeply nested arrays (3+ levels)', function () {
    $result = FormDataProcessor::flatten([
        'a' => [
            'b' => [
                'c' => [
                    'd' => 'value',
                ],
            ],
        ],
    ]);

    expect($result)->toBe([
        'a[b][c][d]' => 'value',
    ]);
});

it('handles deeply nested arrays with multiple leaf values', function () {
    $result = FormDataProcessor::flatten([
        'a' => [
            'b' => [
                'x' => 1,
                'y' => 2,
            ],
            'c' => 3,
        ],
    ]);

    expect($result)->toBe([
        'a[b][x]' => '1',
        'a[b][y]' => '2',
        'a[c]' => '3',
    ]);
});

it('handles mixed associative and list nesting', function () {
    $result = FormDataProcessor::flatten([
        'contacts' => [
            [
                'name' => 'Alice',
                'phone' => '111',
            ],
            [
                'name' => 'Bob',
                'phone' => '222',
            ],
        ],
    ]);

    expect($result)->toBe([
        'contacts[0][name]' => 'Alice',
        'contacts[0][phone]' => '111',
        'contacts[1][name]' => 'Bob',
        'contacts[1][phone]' => '222',
    ]);
});

it('handles top-level list array', function () {
    $result = FormDataProcessor::flatten(['a', 'b', 'c']);

    expect($result)->toBe([
        '0' => 'a',
        '1' => 'b',
        '2' => 'c',
    ]);
});

it('includes resource values directly without string conversion', function () {
    $resource = fopen('php://memory', 'r');
    $result = FormDataProcessor::flatten(['file' => $resource]);

    expect($result)->toHaveKey('file');
    expect(is_resource($result['file']))->toBeTrue();
    expect($result['file'])->toBe($resource);

    fclose($resource);
});

it('handles deep nesting with list at leaf', function () {
    $result = FormDataProcessor::flatten([
        'data' => [
            'tags' => ['php', 'laravel'],
        ],
    ]);

    expect($result)->toBe([
        'data[tags][0]' => 'php',
        'data[tags][1]' => 'laravel',
    ]);
});

it('handles single-element nested array', function () {
    $result = FormDataProcessor::flatten([
        'meta' => ['version' => '1.0'],
    ]);

    expect($result)->toBe([
        'meta[version]' => '1.0',
    ]);
});

it('converts empty inner array to string "Array" via toString', function () {
    set_error_handler(fn () => true);
    $result = FormDataProcessor::flatten([
        'name' => 'John',
        'empty' => [],
        'age' => 30,
    ]);
    restore_error_handler();

    expect($result)->toBe([
        'name' => 'John',
        'empty' => 'Array',
        'age' => '30',
    ]);
});

it('converts empty inner array inside nested structure to string', function () {
    set_error_handler(fn () => true);
    $result = FormDataProcessor::flatten([
        'data' => [
            'items' => [],
            'count' => 0,
        ],
    ]);
    restore_error_handler();

    expect($result)->toBe([
        'data[items]' => 'Array',
        'data[count]' => '0',
    ]);
});

it('converts boolean values to strings in flatten output', function () {
    $result = FormDataProcessor::flatten([
        'options' => [
            'active' => true,
            'verified' => false,
        ],
    ]);

    expect($result)->toBe([
        'options[active]' => 'true',
        'options[verified]' => 'false',
    ]);
});
