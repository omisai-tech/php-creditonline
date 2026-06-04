<?php

use Omisai\CreditOnline\FormDataProcessor;
use Omisai\CreditOnline\Model\Address;

// ---------------------------------------------------------------------------
// Helpers / fixtures
// ---------------------------------------------------------------------------

/**
 * Create an Address model with default values.
 */
$createAddress = function (array $data = []): Address {
    return new Address(array_merge([
        'country_code' => 'HU',
        'zip' => '1234',
        'city' => 'Budapest',
        'street' => 'Main Street',
        'place_type' => 'utca',
        'house_number' => '1',
    ], $data));
};

/**
 * Create a temp file and return an SplFileObject pointing to it.
 */
$createTempSplFile = function (string $content = 'test content'): SplFileObject {
    $path = tempnam(sys_get_temp_dir(), 'fpdt_');
    file_put_contents($path, $content);

    return new SplFileObject($path, 'r');
};

// ===========================================================================
// 1. has_file defaults to false
// ===========================================================================

it('has_file defaults to false', function () {
    $processor = new FormDataProcessor();
    expect($processor->has_file)->toBeFalse();
});

// ===========================================================================
// 2. prepare() with simple scalar values
// ===========================================================================

it('prepare() passes string values through unchanged', function () {
    $processor = new FormDataProcessor();
    $result = $processor->prepare(['name' => 'John']);
    expect($result)->toBe(['name' => 'John']);
});

it('prepare() converts int values to string', function () {
    $processor = new FormDataProcessor();
    $result = $processor->prepare(['age' => 42]);
    expect($result)->toBe(['age' => '42']);
});

it('prepare() converts float values to string', function () {
    $processor = new FormDataProcessor();
    $result = $processor->prepare(['price' => 9.99]);
    expect($result)->toBe(['price' => '9.99']);
});

it('prepare() converts bool true to string', function () {
    $processor = new FormDataProcessor();
    $result = $processor->prepare(['active' => true]);
    expect($result)->toBe(['active' => 'true']);
});

it('prepare() converts bool false to string', function () {
    $processor = new FormDataProcessor();
    $result = $processor->prepare(['active' => false]);
    expect($result)->toBe(['active' => 'false']);
});

it('prepare() handles multiple scalar types in one call', function () {
    $processor = new FormDataProcessor();
    $result = $processor->prepare([
        'name' => 'Alice',
        'age' => 30,
        'active' => true,
        'score' => 4.5,
    ]);
    expect($result)->toBe([
        'name' => 'Alice',
        'age' => '30',
        'active' => 'true',
        'score' => '4.5',
    ]);
});

// ===========================================================================
// 3. prepare() with DateTime values
// ===========================================================================

it('prepare() converts DateTime to ISO8601 string', function () {
    $processor = new FormDataProcessor();
    $dt = new DateTime('2025-01-15T10:20:30', new DateTimeZone('UTC'));
    $result = $processor->prepare(['created_at' => $dt]);
    expect($result)->toBe(['created_at' => '2025-01-15T10:20:30+00:00']);
});

it('prepare() converts DateTime inside nested array to ISO8601 string', function () {
    $processor = new FormDataProcessor();
    $dt = new DateTime('2025-06-04T12:00:00', new DateTimeZone('UTC'));
    $result = $processor->prepare(['meta' => ['timestamp' => $dt, 'label' => 'test']]);
    expect($result)->toBe(['meta' => ['timestamp' => '2025-06-04T12:00:00+00:00', 'label' => 'test']]);
});

// ===========================================================================
// 4. prepare() with null values
// ===========================================================================

it('prepare() skips null values', function () {
    $processor = new FormDataProcessor();
    $result = $processor->prepare(['name' => 'John', 'middle' => null, 'last' => 'Doe']);
    expect($result)->not->toHaveKey('middle');
    expect($result)->toBe(['name' => 'John', 'last' => 'Doe']);
});

it('prepare() converts null values inside nested arrays to empty string', function () {
    $processor = new FormDataProcessor();
    $result = $processor->prepare([
        'user' => [
            'name' => 'Jane',
            'nickname' => null,
            'active' => true,
        ],
    ]);
    expect($result)->toBe([
        'user' => [
            'name' => 'Jane',
            'nickname' => '',
            'active' => 'true',
        ],
    ]);
});

it('prepare() returns empty array when all values are null', function () {
    $processor = new FormDataProcessor();
    $result = $processor->prepare(['a' => null, 'b' => null]);
    expect($result)->toBe([]);
});

// ===========================================================================
// 5. prepare() with nested arrays
// ===========================================================================

it('prepare() recurses into nested associative arrays', function () {
    $processor = new FormDataProcessor();
    $result = $processor->prepare([
        'user' => [
            'name' => 'John',
            'age' => 25,
        ],
    ]);
    expect($result)->toBe([
        'user' => [
            'name' => 'John',
            'age' => '25',
        ],
    ]);
});

it('prepare() recurses into nested list arrays', function () {
    $processor = new FormDataProcessor();
    $result = $processor->prepare([
        'tags' => ['php', 'api', 'rest'],
    ]);
    expect($result)->toBe([
        'tags' => ['php', 'api', 'rest'],
    ]);
});

it('prepare() handles deeply nested arrays', function () {
    $processor = new FormDataProcessor();
    $result = $processor->prepare([
        'a' => ['b' => ['c' => ['d' => 'deep']]],
    ]);
    expect($result)->toBe([
        'a' => ['b' => ['c' => ['d' => 'deep']]],
    ]);
});

it('prepare() handles mixed nested arrays with various types', function () {
    $processor = new FormDataProcessor();
    $dt = new DateTime('2025-01-01T00:00:00', new DateTimeZone('UTC'));
    $result = $processor->prepare([
        'data' => [
            'items' => ['a', 'b'],
            'count' => 2,
            'created' => $dt,
            'active' => true,
        ],
    ]);
    expect($result)->toBe([
        'data' => [
            'items' => ['a', 'b'],
            'count' => '2',
            'created' => '2025-01-01T00:00:00+00:00',
            'active' => 'true',
        ],
    ]);
});

// ===========================================================================
// 6. prepare() with ModelInterface objects (Address)
// ===========================================================================

it('prepare() processes Address model converting all string properties', function () use ($createAddress) {
    $processor = new FormDataProcessor();
    $address = $createAddress([
        'country_code' => 'HU',
        'zip' => '1234',
        'city' => 'Budapest',
        'street' => 'Fő utca',
        'place_type' => 'utca',
        'house_number' => '42',
    ]);
    $result = $processor->prepare(['address' => $address]);
    expect($result)->toBe([
        'address' => [
            'country_code' => 'HU',
            'zip' => '1234',
            'city' => 'Budapest',
            'street' => 'Fő utca',
            'place_type' => 'utca',
            'house_number' => '42',
        ],
    ]);
});

it('prepare() processes Address model with partial fields', function () {
    $processor = new FormDataProcessor();
    $address = new Address([
        'country_code' => 'DE',
        'city' => 'Berlin',
    ]);
    $result = $processor->prepare(['address' => $address]);
    expect($result)->toBe([
        'address' => [
            'country_code' => 'DE',
            'city' => 'Berlin',
        ],
    ]);
});

it('prepare() processes array of Address models', function () use ($createAddress) {
    $processor = new FormDataProcessor();
    $addr1 = $createAddress(['country_code' => 'HU', 'city' => 'Budapest']);
    $addr2 = $createAddress(['country_code' => 'AT', 'city' => 'Wien']);
    $result = $processor->prepare(['addresses' => [$addr1, $addr2]]);
    expect($result)->toBe([
        'addresses' => [
            [
                'country_code' => 'HU',
                'zip' => '1234',
                'city' => 'Budapest',
                'street' => 'Main Street',
                'place_type' => 'utca',
                'house_number' => '1',
            ],
            [
                'country_code' => 'AT',
                'zip' => '1234',
                'city' => 'Wien',
                'street' => 'Main Street',
                'place_type' => 'utca',
                'house_number' => '1',
            ],
        ],
    ]);
});

it('prepare() processes nested Address model inside nested array', function () use ($createAddress) {
    $processor = new FormDataProcessor();
    $address = $createAddress(['city' => 'Szeged']);
    $result = $processor->prepare([
        'person' => [
            'name' => 'John',
            'address' => $address,
        ],
    ]);
    expect($result)->toBe([
        'person' => [
            'name' => 'John',
            'address' => [
                'country_code' => 'HU',
                'zip' => '1234',
                'city' => 'Szeged',
                'street' => 'Main Street',
                'place_type' => 'utca',
                'house_number' => '1',
            ],
        ],
    ]);
});

// ===========================================================================
// 7. prepare() sets has_file when resource/SplFileObject present
// ===========================================================================

it('prepare() sets has_file when SplFileObject is present', function () use ($createTempSplFile) {
    $processor = new FormDataProcessor();
    $file = $createTempSplFile('hello world');
    $processor->prepare(['document' => $file]);
    expect($processor->has_file)->toBeTrue();
});

it('prepare() sets has_file when resource is present', function () {
    $processor = new FormDataProcessor();
    $resource = fopen('php://temp', 'r');
    $processor->prepare(['file' => $resource]);
    expect($processor->has_file)->toBeTrue();
    fclose($resource);
});

it('prepare() sets has_file when SplFileObject is nested inside array', function () use ($createTempSplFile) {
    $processor = new FormDataProcessor();
    $file = $createTempSplFile('nested content');
    $processor->prepare([
        'attachments' => [$file],
    ]);
    expect($processor->has_file)->toBeTrue();
});

it('prepare() does not set has_file when no file data is present', function () {
    $processor = new FormDataProcessor();
    $processor->prepare(['name' => 'John', 'age' => 30]);
    expect($processor->has_file)->toBeFalse();
});

// ===========================================================================
// 8. has_file resets to false on next prepare() call
// ===========================================================================

it('has_file resets to false on next prepare() call', function () use ($createTempSplFile) {
    $processor = new FormDataProcessor();
    $file = $createTempSplFile('some content');
    $processor->prepare(['doc' => $file]);
    expect($processor->has_file)->toBeTrue();

    $processor->prepare(['name' => 'John']);
    expect($processor->has_file)->toBeFalse();
});

// ===========================================================================
// 9. flatten() static method — flat arrays
// ===========================================================================

it('flatten() returns flat associative array unchanged but values toStringd', function () {
    $result = FormDataProcessor::flatten(['a' => 1, 'b' => 2]);
    expect($result)->toBe(['a' => '1', 'b' => '2']);
});

it('flatten() returns flat list array with numeric indices', function () {
    $result = FormDataProcessor::flatten(['x', 'y', 'z']);
    expect($result)->toBe(['0' => 'x', '1' => 'y', '2' => 'z']);
});

it('flatten() handles single element flat array', function () {
    $result = FormDataProcessor::flatten(['key' => 'value']);
    expect($result)->toBe(['key' => 'value']);
});

// ===========================================================================
// 10. flatten() — nested associatives (bracket notation)
// ===========================================================================

it('flatten() produces bracket notation for nested associative arrays', function () {
    $result = FormDataProcessor::flatten(['user' => ['name' => 'John', 'age' => 30]]);
    expect($result)->toBe([
        'user[name]' => 'John',
        'user[age]' => '30',
    ]);
});

it('flatten() produces bracket notation for nested associative with string keys', function () {
    $result = FormDataProcessor::flatten(['filter' => ['type' => 'foo', 'status' => 'bar']]);
    expect($result)->toBe([
        'filter[type]' => 'foo',
        'filter[status]' => 'bar',
    ]);
});

// ===========================================================================
// 11. flatten() — nested lists
// ===========================================================================

it('flatten() produces bracket notation for nested list arrays', function () {
    $result = FormDataProcessor::flatten([[1, 2], [3, 4]]);
    expect($result)->toBe([
        '0[0]' => '1',
        '0[1]' => '2',
        '1[0]' => '3',
        '1[1]' => '4',
    ]);
});

it('flatten() handles list with brackets for single-level list', function () {
    $result = FormDataProcessor::flatten(['items' => [10, 20, 30]]);
    expect($result)->toBe([
        'items[0]' => '10',
        'items[1]' => '20',
        'items[2]' => '30',
    ]);
});

// ===========================================================================
// 12. flatten() — empty arrays
// ===========================================================================

it('flatten() handles empty top-level associative value (suppressed warning)', function () {
    set_error_handler(fn () => true);
    $result = FormDataProcessor::flatten(['x' => []]);
    restore_error_handler();
    expect($result)->toHaveKey('x');
});

it('flatten() handles empty nested list value (suppressed warning)', function () {
    set_error_handler(fn () => true);
    $result = FormDataProcessor::flatten(['data' => ['items' => []]]);
    restore_error_handler();
    expect($result)->toHaveKey('data[items]');
});

it('flatten() returns empty result for empty input array', function () {
    $result = FormDataProcessor::flatten([]);
    expect($result)->toBe([]);
});

// ===========================================================================
// 13. flatten() — boolean values
// ===========================================================================

it('flatten() converts boolean true to string', function () {
    $result = FormDataProcessor::flatten(['active' => true]);
    expect($result)->toBe(['active' => 'true']);
});

it('flatten() converts boolean false to string', function () {
    $result = FormDataProcessor::flatten(['active' => false]);
    expect($result)->toBe(['active' => 'false']);
});

it('flatten() converts nested boolean values to strings', function () {
    $result = FormDataProcessor::flatten(['flags' => ['a' => true, 'b' => false]]);
    expect($result)->toBe([
        'flags[a]' => 'true',
        'flags[b]' => 'false',
    ]);
});

// ===========================================================================
// 14. flatten() — resource values
// ===========================================================================

it('flatten() preserves resource values instead of converting to string', function () {
    $resource = fopen('php://temp', 'r');
    $result = FormDataProcessor::flatten(['file' => $resource]);
    expect($result)->toHaveKey('file');
    expect($result['file'])->toBeResource();
    fclose($resource);
});

it('flatten() preserves resource values in nested arrays', function () {
    $resource = fopen('php://temp', 'r');
    $result = FormDataProcessor::flatten(['attachments' => ['doc' => $resource]]);
    expect($result)->toHaveKey('attachments[doc]');
    expect($result['attachments[doc]'])->toBeResource();
    fclose($resource);
});

// ===========================================================================
// 15. flatten() — deep nesting (3+ levels)
// ===========================================================================

it('flatten() handles 3-level deep nesting', function () {
    $result = FormDataProcessor::flatten([
        'a' => ['b' => ['c' => 'value']],
    ]);
    expect($result)->toBe([
        'a[b][c]' => 'value',
    ]);
});

it('flatten() handles 4-level deep nesting', function () {
    $result = FormDataProcessor::flatten([
        'l1' => ['l2' => ['l3' => ['l4' => 'deep']]],
    ]);
    expect($result)->toBe([
        'l1[l2][l3][l4]' => 'deep',
    ]);
});

it('flatten() handles mixed list and associative deep nesting', function () {
    $result = FormDataProcessor::flatten([
        'users' => [
            ['name' => 'Alice', 'age' => 30],
            ['name' => 'Bob', 'age' => 25],
        ],
    ]);
    expect($result)->toBe([
        'users[0][name]' => 'Alice',
        'users[0][age]' => '30',
        'users[1][name]' => 'Bob',
        'users[1][age]' => '25',
    ]);
});

// ===========================================================================
// 16. flatten() — numeric and float values
// ===========================================================================

it('flatten() converts integer zero to string', function () {
    $result = FormDataProcessor::flatten(['count' => 0]);
    expect($result)->toBe(['count' => '0']);
});

it('flatten() converts float to string', function () {
    $result = FormDataProcessor::flatten(['ratio' => 0.5]);
    expect($result)->toBe(['ratio' => '0.5']);
});

it('flatten() converts zero float to string', function () {
    $result = FormDataProcessor::flatten(['ratio' => 0.0]);
    expect($result)->toBe(['ratio' => '0']);
});

// ===========================================================================
// 17. flatten() — prefix parameter
// ===========================================================================

it('flatten() uses prefix parameter for root key', function () {
    $result = FormDataProcessor::flatten(['name' => 'John', 'age' => 30], 'user');
    expect($result)->toBe([
        'user[name]' => 'John',
        'user[age]' => '30',
    ]);
});

it('flatten() uses prefix parameter with nested data', function () {
    $result = FormDataProcessor::flatten(['address' => ['city' => 'Budapest', 'zip' => '1234']], 'person');
    expect($result)->toBe([
        'person[address][city]' => 'Budapest',
        'person[address][zip]' => '1234',
    ]);
});

// ===========================================================================
// 18. flatten() — edge cases
// ===========================================================================

it('flatten() handles mixed types at various nesting levels', function () {
    $result = FormDataProcessor::flatten([
        'config' => [
            'debug' => true,
            'timeout' => 30,
            'ratio' => 1.5,
            'name' => 'app',
        ],
    ]);
    expect($result)->toBe([
        'config[debug]' => 'true',
        'config[timeout]' => '30',
        'config[ratio]' => '1.5',
        'config[name]' => 'app',
    ]);
});

it('flatten() handles null value via toString', function () {
    $result = FormDataProcessor::flatten(['key' => null]);
    expect($result)->toBe(['key' => '']);
});

it('flatten() handles array with mixed list and associatives at same level', function () {
    $result = FormDataProcessor::flatten([
        'items' => ['a', 'b'],
        'meta' => ['count' => 2],
    ]);
    expect($result)->toBe([
        'items[0]' => 'a',
        'items[1]' => 'b',
        'meta[count]' => '2',
    ]);
});

it('flatten() handles list of associative arrays', function () {
    $result = FormDataProcessor::flatten([
        ['key' => 'a', 'val' => 1],
        ['key' => 'b', 'val' => 2],
    ]);
    expect($result)->toBe([
        '0[key]' => 'a',
        '0[val]' => '1',
        '1[key]' => 'b',
        '1[val]' => '2',
    ]);
});

it('flatten() with prefix handles list of associatives', function () {
    $result = FormDataProcessor::flatten([
        ['name' => 'Alice'],
        ['name' => 'Bob'],
    ], 'users');
    expect($result)->toBe([
        'users[0][name]' => 'Alice',
        'users[1][name]' => 'Bob',
    ]);
});
