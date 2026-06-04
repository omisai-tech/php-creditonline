<?php

use Omisai\CreditOnline\Configuration;
use Omisai\CreditOnline\ObjectSerializer;
use Omisai\CreditOnline\Model\Address;
use Omisai\CreditOnline\Model\ModelInterface;

// ---------------------------------------------------------------------------
// Helpers / fixtures
// ---------------------------------------------------------------------------

/**
 * A simple object implementing ModelInterface for tests that need one.
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
 * A plain stdClass-style object that does NOT implement ModelInterface.
 */
$createPlainObject = function (): stdClass {
    $obj = new stdClass();
    $obj->name = 'Test';
    $obj->value = 42;
    return $obj;
};

/**
 * An object that has a toHeaderValue() method (for toHeaderValue tests).
 */
$createHeaderObject = new class {
    public function toHeaderValue(): string
    {
        return 'custom-header-value';
    }
};

// ---------------------------------------------------------------------------
// Enum fixture for sanitizeForSerialization enum validation test
// ---------------------------------------------------------------------------
$enumFixtureClass = new class {
    public static function getAllowableEnumValues(): array
    {
        return ['red', 'green', 'blue'];
    }
};
$enumFixtureClassName = get_class($enumFixtureClass);

// ---------------------------------------------------------------------------
// Custom plain class that does NOT implement ModelInterface
// ---------------------------------------------------------------------------
class CustomPlainObject
{
    public $title = 'TestTitle';
    public $count = 5;
    public $createdAt;
}

// ---------------------------------------------------------------------------
// Nullable model fixture for deserialize tests
// ---------------------------------------------------------------------------
$nullableModelClass = new class implements ModelInterface {
    public const DISCRIMINATOR = null;
    private static $openAPITypes = [
        'name' => 'string',
        'optional_field' => 'string',
    ];
    private static $openAPIFormats = [
        'name' => null,
        'optional_field' => null,
    ];
    private static $openAPINullables = [
        'name' => false,
        'optional_field' => true,
    ];
    private static $attributeMap = [
        'name' => 'Name',
        'optional_field' => 'OptionalField',
    ];
    private static $setters = [
        'name' => 'setName',
        'optional_field' => 'setOptionalField',
    ];
    private static $getters = [
        'name' => 'getName',
        'optional_field' => 'getOptionalField',
    ];
    public $name = null;
    public $optionalField = 'default-value';

    public static function openAPITypes() { return self::$openAPITypes; }
    public static function openAPIFormats() { return self::$openAPIFormats; }
    public static function attributeMap() { return self::$attributeMap; }
    public static function setters() { return self::$setters; }
    public static function getters() { return self::$getters; }
    public function getModelName() { return 'TestNullable'; }
    public function listInvalidProperties() { return []; }
    public function valid() { return true; }
    public static function isNullable(string $property): bool { return self::$openAPINullables[$property] ?? false; }
    public function isNullableSetToNull(string $property): bool { return false; }
    public function setName($value) { $this->name = $value; }
    public function setOptionalField($value) { $this->optionalField = $value; }
    public function getName() { return $this->name; }
    public function getOptionalField() { return $this->optionalField; }
};

// ---------------------------------------------------------------------------
// ModelInterface with an enum-typed property (for sanitizeForSerialization)
// ---------------------------------------------------------------------------
$modelWithEnumProp = new class($enumFixtureClassName) implements ModelInterface {
    public const DISCRIMINATOR = null;
    private string $enumClass;
    private static $openAPITypes = [
        'color' => 'enumClass',
    ];
    private static $openAPIFormats = [
        'color' => null,
    ];
    private static $openAPINullables = [
        'color' => false,
    ];
    private static $attributeMap = [
        'color' => 'Color',
    ];
    private static $setters = [
        'color' => 'setColor',
    ];
    private static $getters = [
        'color' => 'getColor',
    ];
    private $color = 'red';
    private array $openAPINullablesSetToNull = [];

    public function __construct(string $enumClass) {
        $this->enumClass = $enumClass;
        self::$openAPITypes['color'] = $enumClass;
    }
    public static function openAPITypes() { return self::$openAPITypes; }
    public static function openAPIFormats() { return self::$openAPIFormats; }
    public static function attributeMap() { return self::$attributeMap; }
    public static function setters() { return self::$setters; }
    public static function getters() { return self::$getters; }
    public function getModelName() { return 'TestEnumModel'; }
    public function listInvalidProperties() { return []; }
    public function valid() { return true; }
    public static function isNullable(string $property): bool { return self::$openAPINullables[$property] ?? false; }
    public function isNullableSetToNull(string $property): bool { return in_array($property, $this->openAPINullablesSetToNull, true); }
    public function setColor($value) { $this->color = $value; return $this; }
    public function getColor() { return $this->color; }
};

// ---------------------------------------------------------------------------
// ModelInterface with an enum-typed property set to invalid value
// ---------------------------------------------------------------------------
$modelWithInvalidEnumProp = new class($enumFixtureClassName) implements ModelInterface {
    public const DISCRIMINATOR = null;
    private string $enumClass;
    private static $openAPITypes = ['color' => 'enumClass'];
    private static $openAPIFormats = ['color' => null];
    private static $openAPINullables = ['color' => false];
    private static $attributeMap = ['color' => 'Color'];
    private static $setters = ['color' => 'setColor'];
    private static $getters = ['color' => 'getColor'];
    private array $openAPINullablesSetToNull = [];

    public function __construct(string $enumClass) {
        $this->enumClass = $enumClass;
        self::$openAPITypes['color'] = $enumClass;
    }
    public static function openAPITypes() { return self::$openAPITypes; }
    public static function openAPIFormats() { return self::$openAPIFormats; }
    public static function attributeMap() { return self::$attributeMap; }
    public static function setters() { return self::$setters; }
    public static function getters() { return self::$getters; }
    public function getModelName() { return 'TestInvalidEnumModel'; }
    public function listInvalidProperties() { return []; }
    public function valid() { return true; }
    public static function isNullable(string $property): bool { return self::$openAPINullables[$property] ?? false; }
    public function isNullableSetToNull(string $property): bool { return in_array($property, $this->openAPINullablesSetToNull, true); }
    public function setColor($value) { $this->color = $value; return $this; }
    public function getColor() { return 'yellow'; }
};

// ---------------------------------------------------------------------------
// ModelInterface that does NOT reference an enum subclass for a property
// (exercises the non-enum branch: the type IS in the primitives list)
// ---------------------------------------------------------------------------
$modelWithPrimitiveProp = new class implements ModelInterface {
    public const DISCRIMINATOR = null;
    private static $openAPITypes = ['label' => 'string'];
    private static $openAPIFormats = ['label' => null];
    private static $openAPINullables = ['label' => false];
    private static $attributeMap = ['label' => 'Label'];
    private static $setters = ['label' => 'setLabel'];
    private static $getters = ['label' => 'getLabel'];
    private array $openAPINullablesSetToNull = [];
    private $label = 'hello';

    public static function openAPITypes() { return self::$openAPITypes; }
    public static function openAPIFormats() { return self::$openAPIFormats; }
    public static function attributeMap() { return self::$attributeMap; }
    public static function setters() { return self::$setters; }
    public static function getters() { return self::$getters; }
    public function getModelName() { return 'TestPrimitiveModel'; }
    public function listInvalidProperties() { return []; }
    public function valid() { return true; }
    public static function isNullable(string $property): bool { return false; }
    public function isNullableSetToNull(string $property): bool { return false; }
    public function setLabel($value) { $this->label = $value; return $this; }
    public function getLabel() { return $this->label; }
};

// Save and restore default configuration / static state between tests.
$originalConfig = Configuration::getDefaultConfiguration();

afterEach(function () use ($originalConfig) {
    Configuration::setDefaultConfiguration($originalConfig);
    ObjectSerializer::setDateTimeFormat(\DateTime::ATOM);
});

// ===========================================================================
// 1. setDateTimeFormat
// ===========================================================================

it('setDateTimeFormat allows changing the date format used during serialization', function () {
    $dt = new DateTime('2025-01-15 10:20:30', new DateTimeZone('UTC'));

    $default = ObjectSerializer::sanitizeForSerialization($dt);
    expect($default)->toBe('2025-01-15T10:20:30+00:00');

    ObjectSerializer::setDateTimeFormat('Y-m-d');
    $custom = ObjectSerializer::sanitizeForSerialization($dt);
    expect($custom)->toBe('2025-01-15');
});

// ===========================================================================
// 2. sanitizeForSerialization
// ===========================================================================

it('sanitizeForSerialization returns null for null input', function () {
    expect(ObjectSerializer::sanitizeForSerialization(null))->toBeNull();
});

it('sanitizeForSerialization passes strings through unchanged', function () {
    expect(ObjectSerializer::sanitizeForSerialization('hello world'))->toBe('hello world');
});

it('sanitizeForSerialization passes integers through unchanged', function () {
    expect(ObjectSerializer::sanitizeForSerialization(123))->toBe(123);
});

it('sanitizeForSerialization passes floats through unchanged', function () {
    expect(ObjectSerializer::sanitizeForSerialization(3.14))->toBe(3.14);
});

it('sanitizeForSerialization passes booleans through unchanged', function () {
    expect(ObjectSerializer::sanitizeForSerialization(true))->toBeTrue();
    expect(ObjectSerializer::sanitizeForSerialization(false))->toBeFalse();
});

it('sanitizeForSerialization formats DateTime with ATOM by default', function () {
    $dt = new DateTime('2025-06-04 12:00:00', new DateTimeZone('UTC'));
    $result = ObjectSerializer::sanitizeForSerialization($dt);
    expect($result)->toBe('2025-06-04T12:00:00+00:00');
});

it('sanitizeForSerialization formats DateTime to Y-m-d when format is date', function () {
    $dt = new DateTime('2025-06-04 18:45:00', new DateTimeZone('UTC'));
    $result = ObjectSerializer::sanitizeForSerialization($dt, null, 'date');
    expect($result)->toBe('2025-06-04');
});

it('sanitizeForSerialization recursively sanitizes arrays', function () {
    $dt = new DateTime('2025-01-01', new DateTimeZone('UTC'));
    $result = ObjectSerializer::sanitizeForSerialization([
        'name' => 'test',
        'nested' => ['deep' => 'value'],
        'created_at' => $dt,
    ]);
    expect($result)->toBe([
        'name' => 'test',
        'nested' => ['deep' => 'value'],
        'created_at' => '2025-01-01T00:00:00+00:00',
    ]);
});

it('sanitizeForSerialization serializes ModelInterface objects', function () use ($createAddress) {
    $address = $createAddress([
        'country_code' => 'HU',
        'zip' => '1234',
        'city' => 'Budapest',
        'street' => 'Fő utca',
        'place_type' => 'utca',
        'house_number' => '42',
    ]);

    $result = ObjectSerializer::sanitizeForSerialization($address);

    expect($result)->toBeObject();
    expect($result->CountryCode)->toBe('HU');
    expect($result->Zip)->toBe('1234');
    expect($result->City)->toBe('Budapest');
    expect($result->Street)->toBe('Fő utca');
    expect($result->PlaceType)->toBe('utca');
    expect($result->HouseNumber)->toBe('42');
});

it('sanitizeForSerialization serializes non-ModelInterface objects to stdClass', function () use ($createPlainObject) {
    $result = ObjectSerializer::sanitizeForSerialization($createPlainObject());
    expect($result)->toBeObject();
    expect($result->name)->toBe('Test');
    expect($result->value)->toBe(42);
});

// ===========================================================================
// 3. sanitizeFilename
// ===========================================================================

it('sanitizeFilename returns simple filename unchanged', function () {
    expect(ObjectSerializer::sanitizeFilename('sun.gif'))->toBe('sun.gif');
});

it('sanitizeFilename strips directory path with forward slash', function () {
    expect(ObjectSerializer::sanitizeFilename('path/to/file.txt'))->toBe('file.txt');
});

it('sanitizeFilename strips directory path with backslash', function () {
    expect(ObjectSerializer::sanitizeFilename('path\\to\\file.txt'))->toBe('file.txt');
});

it('sanitizeFilename strips directory path with both slashes', function () {
    expect(ObjectSerializer::sanitizeFilename('a/b\\c/d\\image.png'))->toBe('image.png');
});

it('sanitizeFilename handles nested relative path', function () {
    expect(ObjectSerializer::sanitizeFilename('../../sun.gif'))->toBe('sun.gif');
});

// ===========================================================================
// 4. sanitizeTimestamp
// ===========================================================================

it('sanitizeTimestamp returns non-string unchanged', function ($value) {
    expect(ObjectSerializer::sanitizeTimestamp($value))->toBe($value);
})->with([
    'int' => 12345,
    'float' => 3.14,
    'null' => null,
    'bool' => true,
]);

it('sanitizeTimestamp truncates microsecond precision to 6 digits', function () {
    expect(ObjectSerializer::sanitizeTimestamp('2025-01-01T00:00:00.123456789+00:00'))
        ->toBe('2025-01-01T00:00:00.123456+00:00');
});

it('sanitizeTimestamp passes timestamp without microseconds through unchanged', function () {
    expect(ObjectSerializer::sanitizeTimestamp('2025-01-01T00:00:00+00:00'))
        ->toBe('2025-01-01T00:00:00+00:00');
});

it('sanitizeTimestamp passes timestamp with exactly 6 microsecond digits through', function () {
    expect(ObjectSerializer::sanitizeTimestamp('2025-01-01T00:00:00.123456+00:00'))
        ->toBe('2025-01-01T00:00:00.123456+00:00');
});

// ===========================================================================
// 5. toPathValue
// ===========================================================================

it('toPathValue url-encodes a simple string', function () {
    expect(ObjectSerializer::toPathValue('hello world'))->toBe('hello%20world');
});

it('toPathValue url-encodes special characters', function () {
    expect(ObjectSerializer::toPathValue('test/with=symbols&more'))
        ->toBe('test%2Fwith%3Dsymbols%26more');
});

it('toPathValue url-encodes a numeric value', function () {
    expect(ObjectSerializer::toPathValue(42))->toBe('42');
});

// ===========================================================================
// 6. toHeaderValue
// ===========================================================================

it('toHeaderValue uses toHeaderValue method when available on object', function () use ($createHeaderObject) {
    expect(ObjectSerializer::toHeaderValue($createHeaderObject))->toBe('custom-header-value');
});

it('toHeaderValue passes string through via toString', function () {
    expect(ObjectSerializer::toHeaderValue('some-header'))->toBe('some-header');
});

it('toHeaderValue formats DateTime via toString', function () {
    $dt = new DateTime('2025-06-04T12:00:00+00:00');
    $result = ObjectSerializer::toHeaderValue($dt);
    expect($result)->toBe('2025-06-04T12:00:00+00:00');
});

it('toHeaderValue converts boolean via toString', function () {
    expect(ObjectSerializer::toHeaderValue(true))->toBe('true');
    expect(ObjectSerializer::toHeaderValue(false))->toBe('false');
});

it('toHeaderValue uses ModelInterface toHeaderValue', function () use ($createAddress) {
    $address = $createAddress();
    $result = ObjectSerializer::toHeaderValue($address);
    expect($result)->toContain('"CountryCode"');
    expect($result)->toContain('"HU"');
});

// ===========================================================================
// 7. toString
// ===========================================================================

it('toString formats DateTime to configured format', function () {
    $dt = new DateTime('2025-06-04T15:30:00+00:00');
    expect(ObjectSerializer::toString($dt))->toBe('2025-06-04T15:30:00+00:00');
});

it('toString returns true for bool true', function () {
    expect(ObjectSerializer::toString(true))->toBe('true');
});

it('toString returns false for bool false', function () {
    expect(ObjectSerializer::toString(false))->toBe('false');
});

it('toString casts int to string', function () {
    expect(ObjectSerializer::toString(42))->toBe('42');
});

it('toString casts float to string', function () {
    expect(ObjectSerializer::toString(3.14))->toBe('3.14');
});

it('toString casts zero float to string', function () {
    expect(ObjectSerializer::toString(0.0))->toBe('0');
});

// ===========================================================================
// 8. serializeCollection
// ===========================================================================

it('serializeCollection joins with commas for csv (default)', function () {
    expect(ObjectSerializer::serializeCollection(['a', 'b', 'c'], 'csv'))->toBe('a,b,c');
});

it('serializeCollection joins with pipes for pipes style', function () {
    expect(ObjectSerializer::serializeCollection(['a', 'b', 'c'], 'pipes'))->toBe('a|b|c');
});

it('serializeCollection joins with pipes for pipeDelimited style', function () {
    expect(ObjectSerializer::serializeCollection(['a', 'b', 'c'], 'pipeDelimited'))->toBe('a|b|c');
});

it('serializeCollection joins with tabs for tsv', function () {
    expect(ObjectSerializer::serializeCollection(['a', 'b', 'c'], 'tsv'))->toBe("a\tb\tc");
});

it('serializeCollection joins with spaces for ssv', function () {
    expect(ObjectSerializer::serializeCollection(['a', 'b', 'c'], 'ssv'))->toBe('a b c');
});

it('serializeCollection joins with spaces for spaceDelimited', function () {
    expect(ObjectSerializer::serializeCollection(['a', 'b', 'c'], 'spaceDelimited'))->toBe('a b c');
});

it('serializeCollection uses http_build_query for multi with allowCollectionFormatMulti=true', function () {
    $result = ObjectSerializer::serializeCollection(['a', 'b', 'c'], 'multi', true);
    expect($result)->toBe('0=a&1=b&2=c');
});

it('serializeCollection defaults to csv for unknown style', function () {
    expect(ObjectSerializer::serializeCollection(['x', 'y'], 'unknownStyle'))->toBe('x,y');
});

it('serializeCollection handles single element', function () {
    expect(ObjectSerializer::serializeCollection(['only'], 'csv'))->toBe('only');
});

// ===========================================================================
// 9. convertBoolToQueryStringFormat
// ===========================================================================

it('convertBoolToQueryStringFormat returns 1 for true and 0 for false when int format', function () {
    // Default configuration uses BOOLEAN_FORMAT_INT
    expect(ObjectSerializer::convertBoolToQueryStringFormat(true))->toBe(1);
    expect(ObjectSerializer::convertBoolToQueryStringFormat(false))->toBe(0);
});

it('convertBoolToQueryStringFormat returns "true"/"false" when string format', function () {
    $config = new Configuration();
    $config->setBooleanFormatForQueryString(Configuration::BOOLEAN_FORMAT_STRING);
    Configuration::setDefaultConfiguration($config);

    expect(ObjectSerializer::convertBoolToQueryStringFormat(true))->toBe('true');
    expect(ObjectSerializer::convertBoolToQueryStringFormat(false))->toBe('false');
});

// ===========================================================================
// 10. toQueryValue
// ===========================================================================

it('toQueryValue returns empty array for empty non-required value', function () {
    $result = ObjectSerializer::toQueryValue(null, 'param', 'string', 'form', true, false);
    expect($result)->toBe([]);
});

it('toQueryValue returns param with empty string for empty required value', function () {
    $result = ObjectSerializer::toQueryValue(null, 'param', 'string', 'form', true, true);
    expect($result)->toBe(['param' => '']);
});

it('toQueryValue handles simple scalar string value', function () {
    $result = ObjectSerializer::toQueryValue('hello', 'q', 'string', 'form', true, true);
    expect($result)->toBe(['q' => 'hello']);
});

it('toQueryValue handles integer value', function () {
    $result = ObjectSerializer::toQueryValue(42, 'limit', 'int', 'form', true, true);
    expect($result)->toBe(['limit' => 42]);
});

it('toQueryValue handles zero integer as non-empty', function () {
    $result = ObjectSerializer::toQueryValue(0, 'offset', 'int', 'form', true, false);
    expect($result)->toBe(['offset' => 0]);
});

it('toQueryValue handles float value', function () {
    $result = ObjectSerializer::toQueryValue(3.14, 'pi', 'float', 'form', true, true);
    expect($result)->toBe(['pi' => 3.14]);
});

it('toQueryValue handles false boolean as non-empty', function () {
    $result = ObjectSerializer::toQueryValue(false, 'flag', 'boolean', 'form', true, true);
    expect($result)->toBe(['flag' => 0]);
});

it('toQueryValue handles true boolean as non-empty', function () {
    $result = ObjectSerializer::toQueryValue(true, 'flag', 'boolean', 'form', true, true);
    expect($result)->toBe(['flag' => 1]);
});

it('toQueryValue handles DateTime value', function () {
    $dt = new DateTime('2025-06-04T10:00:00', new DateTimeZone('UTC'));
    $result = ObjectSerializer::toQueryValue($dt, 'from', '\\DateTime', 'form', true, true);
    expect($result)->toBe(['from' => '2025-06-04T10:00:00+00:00']);
});

it('toQueryValue handles array with form style and explode', function () {
    $result = ObjectSerializer::toQueryValue(['a', 'b', 'c'], 'items', 'array', 'form', true, true);
    expect($result)->toBe(['items' => ['a', 'b', 'c']]);
});

it('toQueryValue handles array with deepObject style', function () {
    $result = ObjectSerializer::toQueryValue(['name' => 'John', 'age' => 30], 'filter', 'object', 'deepObject', true, true);
    expect($result)->toBe(['filter[name]' => 'John', 'filter[age]' => 30]);
});

it('toQueryValue handles empty string as empty for string type non-required', function () {
    $result = ObjectSerializer::toQueryValue('', 'param', 'string', 'form', true, false);
    expect($result)->toBe([]);
});

it('toQueryValue handles empty string as empty for string type required', function () {
    $result = ObjectSerializer::toQueryValue('', 'param', 'string', 'form', true, true);
    expect($result)->toBe(['param' => '']);
});

it('toQueryValue handles array without explode collects keys', function () {
    $result = ObjectSerializer::toQueryValue(['x', 'y'], 'items', 'array', 'form', false, true);
    expect($result)->toBe(['items' => 'x,y']);
});

it('toQueryValue handles object without explode with form style', function () {
    $result = ObjectSerializer::toQueryValue(['a' => 1], 'obj', 'object', 'form', false, true);
    expect($result)->toBe(['obj' => 'a,1']);
});

// ===========================================================================
// 11. buildQuery
// ===========================================================================

it('buildQuery returns empty string for empty params', function () {
    expect(ObjectSerializer::buildQuery([]))->toBe('');
});

it('buildQuery builds simple key-value pairs', function () {
    expect(ObjectSerializer::buildQuery(['key' => 'value']))
        ->toBe('key=value');
});

it('buildQuery encodes multiple key-value pairs', function () {
    $result = ObjectSerializer::buildQuery(['a' => '1', 'b' => '2']);
    expect($result)->toContain('a=1');
    expect($result)->toContain('b=2');
    expect($result)->toContain('&');
});

it('buildQuery encodes boolean values using int format by default', function () {
    $result = ObjectSerializer::buildQuery(['active' => true]);
    expect($result)->toBe('active=1');
});

it('buildQuery encodes boolean values using string format', function () {
    $config = new Configuration();
    $config->setBooleanFormatForQueryString(Configuration::BOOLEAN_FORMAT_STRING);
    Configuration::setDefaultConfiguration($config);

    $result = ObjectSerializer::buildQuery(['active' => true]);
    expect($result)->toBe('active=true');
});

it('buildQuery handles nested array values', function () {
    $result = ObjectSerializer::buildQuery(['filter' => ['type' => 'foo', 'status' => 'bar']]);
    expect($result)->toBe('filter=foo&filter=bar');
});

it('buildQuery encodes with RFC3986 (rawurlencode)', function () {
    $result = ObjectSerializer::buildQuery(['q' => 'hello world'], PHP_QUERY_RFC3986);
    expect($result)->toBe('q=hello%20world');
});

it('buildQuery encodes with RFC1738 (urlencode)', function () {
    $result = ObjectSerializer::buildQuery(['q' => 'hello world'], PHP_QUERY_RFC1738);
    expect($result)->toBe('q=hello+world');
});

it('buildQuery does not encode when encoding is false', function () {
    $result = ObjectSerializer::buildQuery(['q' => 'hello world'], false);
    expect($result)->toBe('q=hello world');
});

it('buildQuery throws InvalidArgumentException for invalid encoding', function () {
    ObjectSerializer::buildQuery(['key' => 'value'], 999);
})->throws(\InvalidArgumentException::class);

it('buildQuery skips null values', function () {
    $result = ObjectSerializer::buildQuery(['a' => 1, 'b' => null, 'c' => 3]);
    expect($result)->not->toContain('b=');
    expect($result)->toContain('a=1');
    expect($result)->toContain('c=3');
});

it('buildQuery handles array with integer indices', function () {
    $result = ObjectSerializer::buildQuery(['ids' => [1, 2, 3]]);
    expect($result)->toBe('ids=1&ids=2&ids=3');
});

// ===========================================================================
// 12. deserialize
// ===========================================================================

it('deserialize returns null for null data', function () {
    expect(ObjectSerializer::deserialize(null, 'string'))->toBeNull();
});

it('deserialize handles array type (class ending with [])', function () {
    $result = ObjectSerializer::deserialize(['hello', 'world'], 'string[]');
    expect($result)->toBe(['hello', 'world']);
});

it('deserialize handles array type with empty array', function () {
    $result = ObjectSerializer::deserialize([], 'string[]');
    expect($result)->toBe([]);
});

it('deserialize throws for non-array data with array type', function () {
    ObjectSerializer::deserialize('not-an-array', 'string[]');
})->throws(\InvalidArgumentException::class);

it('deserialize returns string as string', function () {
    expect(ObjectSerializer::deserialize('hello', 'string'))->toBe('hello');
});

it('deserialize casts to int', function () {
    $result = ObjectSerializer::deserialize('42', 'int');
    expect($result)->toBe(42);
    expect($result)->toBeInt();
});

it('deserialize casts to float', function () {
    $result = ObjectSerializer::deserialize('3.14', 'float');
    expect($result)->toBe(3.14);
    expect($result)->toBeFloat();
});

it('deserialize casts to bool', function () {
    expect(ObjectSerializer::deserialize(1, 'bool'))->toBeTrue();
});

it('deserialize handles array type', function () {
    $result = ObjectSerializer::deserialize(['a', 'b'], 'array');
    expect($result)->toBe(['a', 'b']);
});

it('deserialize casts to bool from string falsy value', function () {
    expect(ObjectSerializer::deserialize('0', 'bool'))->toBeFalse();
});

it('deserialize handles string with model class ending non-[], non-special', function () {
    // Passing a simple string to a Model class that is not in the special-types list
    // should go through model deserialization path
    $json = json_encode(['CountryCode' => 'HU', 'Zip' => '1111', 'City' => 'Pécs', 'Street' => 'Kossuth', 'PlaceType' => 'utca', 'HouseNumber' => '5']);
    $result = ObjectSerializer::deserialize($json, Address::class);
    expect($result)->toBeInstanceOf(Address::class);
    expect($result->getCity())->toBe('Pécs');
});

it('deserialize handles float from int-like string', function () {
    $result = ObjectSerializer::deserialize('7.5', 'float');
    expect($result)->toBe(7.5);
});

it('deserialize creates DateTime from valid string', function () {
    $result = ObjectSerializer::deserialize('2025-06-04T12:00:00+00:00', '\DateTime');
    expect($result)->toBeInstanceOf(DateTime::class);
    expect($result->format(DateTime::ATOM))->toBe('2025-06-04T12:00:00+00:00');
});

it('deserialize returns null for empty DateTime string', function () {
    expect(ObjectSerializer::deserialize('', '\DateTime'))->toBeNull();
});

it('deserialize fixes overly precise DateTime via sanitizeTimestamp', function () {
    $result = ObjectSerializer::deserialize('2025-01-01T00:00:00.123456789+00:00', '\DateTime');
    expect($result)->toBeInstanceOf(DateTime::class);
    expect($result->format('u'))->toBe('123456');
});

it('deserialize creates Address model from array data', function () {
    $data = [
        'CountryCode' => 'HU',
        'Zip' => '1234',
        'City' => 'Budapest',
        'Street' => 'Fő utca',
        'PlaceType' => 'utca',
        'HouseNumber' => '42',
    ];

    $result = ObjectSerializer::deserialize($data, Address::class);

    expect($result)->toBeInstanceOf(Address::class);
    expect($result->getCountryCode())->toBe('HU');
    expect($result->getZip())->toBe('1234');
    expect($result->getCity())->toBe('Budapest');
    expect($result->getStreet())->toBe('Fő utca');
    expect($result->getPlaceType())->toBe('utca');
    expect($result->getHouseNumber())->toBe('42');
});

it('deserialize creates Address model from JSON string', function () {
    $json = json_encode([
        'CountryCode' => 'DE',
        'Zip' => '10115',
        'City' => 'Berlin',
        'Street' => 'Unter den Linden',
        'PlaceType' => 'strasse',
        'HouseNumber' => '1',
    ]);

    $result = ObjectSerializer::deserialize($json, Address::class);

    expect($result)->toBeInstanceOf(Address::class);
    expect($result->getCountryCode())->toBe('DE');
    expect($result->getZip())->toBe('10115');
});

it('deserialize creates Address model from stdClass data', function () {
    $data = (object) [
        'CountryCode' => 'AT',
        'Zip' => '1010',
        'City' => 'Wien',
        'Street' => 'Stephansplatz',
        'PlaceType' => 'platz',
        'HouseNumber' => '1',
    ];

    $result = ObjectSerializer::deserialize($data, Address::class);

    expect($result)->toBeInstanceOf(Address::class);
    expect($result->getCountryCode())->toBe('AT');
});

it('deserialize handles object type', function () {
    $result = ObjectSerializer::deserialize(['key' => 'value'], 'object');
    expect($result)->toBe(['key' => 'value']);
});

it('deserialize handles mixed type from string', function () {
    $result = ObjectSerializer::deserialize('just a string', 'mixed');
    expect($result)->toBe('just a string');
});

it('deserialize handles mixed type from int', function () {
    $result = ObjectSerializer::deserialize(5, 'mixed');
    expect($result)->toBe(5);
});

it('deserialize handles mixed type from array', function () {
    $result = ObjectSerializer::deserialize(['a', 'b'], 'mixed');
    expect($result)->toBe(['a', 'b']);
});

it('deserialize validates enum values', function () {
    // Create a mock enum class inline
    $enumClass = new class {
        public static function getAllowableEnumValues(): array
        {
            return ['red', 'green', 'blue'];
        }
    };

    $result = ObjectSerializer::deserialize('red', get_class($enumClass));
    expect($result)->toBe('red');
});

it('deserialize throws for invalid enum value', function () {
    $enumClass = new class {
        public static function getAllowableEnumValues(): array
        {
            return ['red', 'green', 'blue'];
        }
    };

    ObjectSerializer::deserialize('yellow', get_class($enumClass));
})->throws(\InvalidArgumentException::class);

it('deserialize handles float with decimal string', function () {
    $result = ObjectSerializer::deserialize('2.718', 'float');
    expect($result)->toBe(2.718);
});

it('deserialize handles associative array type like array<string,int>', function () {
    $result = ObjectSerializer::deserialize(['a' => 1, 'b' => 2], 'array<string,int>');
    expect($result)->toBe(['a' => 1, 'b' => 2]);
});

it('deserialize handles map type like map[string,int]', function () {
    $result = ObjectSerializer::deserialize(['x' => 10], 'map[string,int]');
    expect($result)->toBe(['x' => 10]);
});

it('deserialize recursively deserializes items in array type', function () {
    $result = ObjectSerializer::deserialize(['2025-01-01T00:00:00+00:00'], '\DateTime[]');
    expect($result)->toBeArray()->toHaveCount(1);
    expect($result[0])->toBeInstanceOf(DateTime::class);
});

// ===========================================================================
// 13. sanitizeForSerialization — enum validation (lines ~95-98)
// ===========================================================================

it('sanitizeForSerialization accepts valid enum value for ModelInterface property', function () use ($modelWithEnumProp) {
    $result = ObjectSerializer::sanitizeForSerialization($modelWithEnumProp);
    expect($result)->toBeObject();
    expect($result->Color)->toBe('red');
});

it('sanitizeForSerialization throws for invalid enum value on ModelInterface property', function () use ($modelWithInvalidEnumProp) {
    ObjectSerializer::sanitizeForSerialization($modelWithInvalidEnumProp);
})->throws(\InvalidArgumentException::class, "Invalid value for enum");

// ===========================================================================
// 14. sanitizeForSerialization — non-ModelInterface objects (lines ~107-108)
// ===========================================================================

it('sanitizeForSerialization serializes custom non-ModelInterface class', function () {
    $obj = new CustomPlainObject();
    $this->createdAt = new DateTime('2025-06-04', new DateTimeZone('UTC'));
    $obj->createdAt = $this->createdAt;

    $result = ObjectSerializer::sanitizeForSerialization($obj);

    expect($result)->toBeObject();
    expect($result->title)->toBe('TestTitle');
    expect($result->count)->toBe(5);
    expect($result->createdAt)->toBe('2025-06-04T00:00:00+00:00');
});

// ===========================================================================
// 15. toQueryValue — isEmptyValue coverage through toQueryValue (lines 185-204)
// ===========================================================================

it('toQueryValue treats int 0 as non-empty (required)', function () {
    $result = ObjectSerializer::toQueryValue(0, 'offset', 'int', 'form', true, true);
    expect($result)->toBe(['offset' => 0]);
});

it('toQueryValue treats integer 0 as non-empty (non-required)', function () {
    $result = ObjectSerializer::toQueryValue(0, 'offset', 'integer', 'form', true, false);
    expect($result)->toBe(['offset' => 0]);
});

it('toQueryValue treats float 0.0 as non-empty (required)', function () {
    $result = ObjectSerializer::toQueryValue(0.0, 'ratio', 'float', 'form', true, true);
    expect($result)->toBe(['ratio' => 0.0]);
});

it('toQueryValue treats float 0.0 as non-empty (non-required)', function () {
    $result = ObjectSerializer::toQueryValue(0.0, 'ratio', 'number', 'form', true, false);
    expect($result)->toBe(['ratio' => 0.0]);
});

it('toQueryValue treats boolean false as non-empty (required)', function () {
    $result = ObjectSerializer::toQueryValue(false, 'flag', 'boolean', 'form', true, true);
    expect($result)->toBe(['flag' => 0]);
});

it('toQueryValue treats boolean false as non-empty (non-required)', function () {
    $result = ObjectSerializer::toQueryValue(false, 'flag', 'boolean', 'form', true, false);
    expect($result)->toBe(['flag' => 0]);
});

it('toQueryValue treats empty string as empty (required returns empty string param)', function () {
    $result = ObjectSerializer::toQueryValue('', 'q', 'string', 'form', true, true);
    expect($result)->toBe(['q' => '']);
});

it('toQueryValue treats empty string as empty (non-required returns empty array)', function () {
    $result = ObjectSerializer::toQueryValue('', 'q', 'string', 'form', true, false);
    expect($result)->toBe([]);
});

it('toQueryValue treats null with other type as empty (non-required returns empty array)', function () {
    $result = ObjectSerializer::toQueryValue(null, 'p', 'array', 'form', true, false);
    expect($result)->toBe([]);
});

it('toQueryValue treats null with other type as empty (required returns empty string param)', function () {
    $result = ObjectSerializer::toQueryValue(null, 'p', 'object', 'form', true, true);
    expect($result)->toBe(['p' => '']);
});

// ===========================================================================
// 16. toQueryValue — edge cases (lines 275, 279, 283)
// ===========================================================================

it('toQueryValue handles array type with deepObject style and explode', function () {
    $result = ObjectSerializer::toQueryValue(['a', 'b'], 'items', 'array', 'deepObject', true, true);
    expect($result)->toBe(['items[0]' => 'a', 'items[1]' => 'b']);
});

it('toQueryValue handles object type with form style and explode', function () {
    $result = ObjectSerializer::toQueryValue(['key' => 'val'], 'param', 'object', 'form', true, true);
    expect($result)->toBe(['key' => 'val']);
});

it('toQueryValue handles object type with deepObject style and explode', function () {
    $result = ObjectSerializer::toQueryValue(['x' => 1, 'y' => 2], 'filter', 'object', 'deepObject', true, true);
    expect($result)->toBe(['filter[x]' => 1, 'filter[y]' => 2]);
});

it('toQueryValue converts boolean to int when in query value explode mode', function () {
    $result = ObjectSerializer::toQueryValue(true, 'active', 'boolean', 'form', true, true);
    expect($result)->toBe(['active' => 1]);
});

it('toQueryValue converts boolean false to 0', function () {
    $result = ObjectSerializer::toQueryValue(false, 'active', 'boolean', 'form', true, true);
    expect($result)->toBe(['active' => 0]);
});

it('toQueryValue handles deepObject style with nested arrays', function () {
    $result = ObjectSerializer::toQueryValue(
        ['user' => ['name' => 'John', 'tags' => ['a', 'b']]],
        'filter',
        'object',
        'deepObject',
        true,
        true
    );
    expect($result)->toBe([
        'filter[user][name]' => 'John',
        'filter[user][tags][0]' => 'a',
        'filter[user][tags][1]' => 'b',
    ]);
});

// ===========================================================================
// 17. serializeCollection — multi without allowCollectionFormatMulti
// ===========================================================================

it('serializeCollection falls back to csv for multi style when allowCollectionFormatMulti is false', function () {
    $result = ObjectSerializer::serializeCollection(['a', 'b', 'c'], 'multi', false);
    expect($result)->toBe('a,b,c');
});

it('serializeCollection falls back to csv for multi style when allowCollectionFormatMulti not passed', function () {
    $result = ObjectSerializer::serializeCollection(['x', 'y'], 'multi');
    expect($result)->toBe('x,y');
});

// ===========================================================================
// 18. buildQuery — boolean values in nested arrays + RFC1738 edge
// ===========================================================================

it('buildQuery encodes boolean values inside nested arrays', function () {
    $result = ObjectSerializer::buildQuery(['flags' => [true, false, true]]);
    expect($result)->toBe('flags=1&flags=0&flags=1');
});

it('buildQuery encodes nested arrays with booleans using string format', function () {
    $config = new Configuration();
    $config->setBooleanFormatForQueryString(Configuration::BOOLEAN_FORMAT_STRING);
    Configuration::setDefaultConfiguration($config);

    $result = ObjectSerializer::buildQuery(['flags' => [true, false]]);
    expect($result)->toBe('flags=true&flags=false');
});

it('buildQuery RFC1738 encodes spaces as plus', function () {
    $result = ObjectSerializer::buildQuery(['description' => 'foo bar baz'], PHP_QUERY_RFC1738);
    expect($result)->toBe('description=foo+bar+baz');
});

// ===========================================================================
// 19. deserialize — array class ending with [] using JSON string (lines 397-404)
// ===========================================================================

it('deserialize decodes JSON string for array class type', function () {
    $json = json_encode(['hello', 'world']);
    $result = ObjectSerializer::deserialize($json, 'string[]');
    expect($result)->toBe(['hello', 'world']);
});

it('deserialize decodes JSON string and recursively deserializes items', function () {
    $json = json_encode(['2025-06-04T12:00:00+00:00', '2025-07-01T00:00:00+00:00']);
    $result = ObjectSerializer::deserialize($json, '\DateTime[]');
    expect($result)->toBeArray()->toHaveCount(2);
    expect($result[0])->toBeInstanceOf(DateTime::class);
    expect($result[1])->toBeInstanceOf(DateTime::class);
});

// ===========================================================================
// 20. deserialize — associative array with inner type deserialization (lines 416-427)
// ===========================================================================

it('deserialize handles array<string,\DateTime> with inner type deserialization', function () {
    $data = ['created' => '2025-01-01T00:00:00+00:00', 'updated' => '2025-06-04T12:00:00+00:00'];
    $result = ObjectSerializer::deserialize($data, 'array<string,\DateTime>');
    expect($result)->toBeArray();
    expect($result['created'])->toBeInstanceOf(DateTime::class);
    expect($result['updated'])->toBeInstanceOf(DateTime::class);
});

it('deserialize handles map[string,\DateTime] with inner type deserialization', function () {
    $data = ['start' => '2025-01-01T00:00:00+00:00'];
    $result = ObjectSerializer::deserialize($data, 'map[string,\DateTime]');
    expect($result)->toBeArray();
    expect($result['start'])->toBeInstanceOf(DateTime::class);
});

it('deserialize handles array<string,int> from JSON string', function () {
    $json = json_encode(['a' => 10, 'b' => 20]);
    $result = ObjectSerializer::deserialize($json, 'array<string,int>');
    expect($result)->toBe(['a' => 10, 'b' => 20]);
});

// ===========================================================================
// 21. deserialize — object type (line 431-432)
// ===========================================================================

it('deserialize casts scalar to array for object type', function () {
    $result = ObjectSerializer::deserialize('not-an-object', 'object');
    expect($result)->toBe(['not-an-object']);
});

it('deserialize returns array as-is for object type', function () {
    $result = ObjectSerializer::deserialize(['foo' => 'bar'], 'object');
    expect($result)->toBe(['foo' => 'bar']);
});

// ===========================================================================
// 22. deserialize — mixed type (line 434-435)
// ===========================================================================

it('deserialize preserves type for mixed from bool', function () {
    $result = ObjectSerializer::deserialize(true, 'mixed');
    expect($result)->toBeTrue();
});

it('deserialize preserves type for mixed from float', function () {
    $result = ObjectSerializer::deserialize(3.14, 'mixed');
    expect($result)->toBe(3.14);
});

// ===========================================================================
// 23. deserialize — SplFileObject with Content-Disposition (lines 445-455)
// ===========================================================================

it('deserialize creates SplFileObject from stream with Content-Disposition header', function () {
    $tempDir = sys_get_temp_dir() . '/opencode-spl-test-' . uniqid();
    mkdir($tempDir, 0777, true);

    $config = new Configuration();
    $config->setTempFolderPath($tempDir);
    Configuration::setDefaultConfiguration($config);

    $content = 'Hello, World! This is test file content.';
    $result = ObjectSerializer::deserialize($content, '\SplFileObject', [
        'Content-Disposition' => 'inline; filename=testfile.txt',
    ]);

    expect($result)->toBeInstanceOf(SplFileObject::class);
    expect($result->getFilename())->toContain('testfile.txt');
    expect(trim($result->fread(1024)))->toBe($content);

    // Cleanup
    $result = null;
    array_map('unlink', glob($tempDir . '/*'));
    rmdir($tempDir);
});

// ===========================================================================
// 24. deserialize — SplFileObject without Content-Disposition (lines 460-481)
// ===========================================================================

it('deserialize creates SplFileObject from stream without Content-Disposition header', function () {
    $tempDir = sys_get_temp_dir() . '/opencode-spl-test2-' . uniqid();
    mkdir($tempDir, 0777, true);

    $config = new Configuration();
    $config->setTempFolderPath($tempDir);
    Configuration::setDefaultConfiguration($config);

    $content = 'Temporary file content without header.';
    $result = ObjectSerializer::deserialize($content, '\SplFileObject');

    expect($result)->toBeInstanceOf(SplFileObject::class);
    expect(trim($result->fread(1024)))->toBe($content);

    // Cleanup
    $result = null;
    array_map('unlink', glob($tempDir . '/*'));
    rmdir($tempDir);
});

// ===========================================================================
// 25. deserialize — enum validation failure (lines 492-496)
// ===========================================================================

it('deserialize throws InvalidArgumentException for invalid enum value with descriptive message', function () {
    $enumClass = new class {
        public static function getAllowableEnumValues(): array
        {
            return ['red', 'green', 'blue'];
        }
    };

    ObjectSerializer::deserialize('purple', get_class($enumClass));
})->throws(\InvalidArgumentException::class);

// ===========================================================================
// 26. deserialize — discriminator-based deserialization (lines 504-511)
// ===========================================================================

it('deserialize uses discriminator to select subclass', function () {
    // Create a base class and a subclass in the Omisai\CreditOnline\Model namespace
    // using anonymous classes wrapped in the correct namespace via eval
    $baseClassName = 'Omisai\CreditOnline\Model\_DiscriminatorBaseTest_' . uniqid();
    $subClassName = 'Omisai\CreditOnline\Model\_DiscriminatorSubTest_' . uniqid();

    // Extract short names
    $baseShort = substr($baseClassName, strrpos($baseClassName, '\\') + 1);
    $subShort = substr($subClassName, strrpos($subClassName, '\\') + 1);

    eval("
        namespace Omisai\\CreditOnline\\Model;
        class {$baseShort} implements \Omisai\CreditOnline\Model\ModelInterface {
            public const DISCRIMINATOR = 'object_type';
            public static function openAPITypes(): array { return []; }
            public static function openAPIFormats(): array { return []; }
            public static function attributeMap(): array { return []; }
            public static function setters(): array { return []; }
            public static function getters(): array { return []; }
            public function getModelName(): string { return '{$baseShort}'; }
            public function listInvalidProperties(): array { return []; }
            public function valid(): bool { return true; }
            public static function isNullable(string \$property): bool { return false; }
            public function isNullableSetToNull(string \$property): bool { return false; }
        }
        class {$subShort} extends {$baseShort} {
            public function getModelName(): string { return '{$subShort}'; }
        }
    ");

    $data = (object) ['object_type' => $subShort, 'some_prop' => 'test'];

    $result = ObjectSerializer::deserialize($data, $baseClassName);

    expect($result)->toBeInstanceOf($subClassName);
});

// ===========================================================================
// 27. deserialize — model from stdClass with nullable properties (lines 518-534)
// ===========================================================================

it('deserialize sets nullable property to null when attribute is missing from data', function () use ($nullableModelClass) {
    $className = get_class($nullableModelClass);
    $data = (object) ['Name' => 'John'];

    $result = ObjectSerializer::deserialize($data, $className);

    expect($result->name)->toBe('John');
    expect($result->optionalField)->toBeNull();
});

it('deserialize skips non-nullable property when attribute is missing from data', function () use ($nullableModelClass) {
    $className = get_class($nullableModelClass);
    $data = (object) ['OptionalField' => 'custom'];

    $result = ObjectSerializer::deserialize($data, $className);

    expect($result->name)->toBeNull();
    expect($result->optionalField)->toBe('custom');
});

it('deserialize handles all attributes present in data', function () use ($nullableModelClass) {
    $className = get_class($nullableModelClass);
    $data = (object) ['Name' => 'Alice', 'OptionalField' => 'extra'];

    $result = ObjectSerializer::deserialize($data, $className);

    expect($result->name)->toBe('Alice');
    expect($result->optionalField)->toBe('extra');
});

// ===========================================================================
// 28. deserialize — model with primitive property type skips enum check
// ===========================================================================

it('deserialize correctly processes model whose properties are all primitive types', function () use ($modelWithPrimitiveProp) {
    $className = get_class($modelWithPrimitiveProp);
    $data = (object) ['Label' => 'world'];

    $result = ObjectSerializer::deserialize($data, $className);

    expect($result->getLabel())->toBe('world');
});

// ===========================================================================
// 29. sanitizeForSerialization — ModelInterface with non-enum, non-primitive property type
// ===========================================================================

it('sanitizeForSerialization skips enum check for primitive openAPI type', function () use ($modelWithPrimitiveProp) {
    $result = ObjectSerializer::sanitizeForSerialization($modelWithPrimitiveProp);
    expect($result)->toBeObject();
    expect($result->Label)->toBe('hello');
});

// ===========================================================================
// 30. toQueryValue — non-string value handled through toString branch
// ===========================================================================

it('toQueryValue passes integer through as query value without explode', function () {
    $result = ObjectSerializer::toQueryValue(42, 'answer', 'int', 'form', false, true);
    expect($result)->toBe(['answer' => '42']);
});

// ===========================================================================
// 31. serializeCollection — multi with multidimensional array
// ===========================================================================

it('serializeCollection handles multidimensional array with multi style and allowCollectionFormatMulti', function () {
    $result = ObjectSerializer::serializeCollection(['a' => ['b', 'c']], 'multi', true);
    expect($result)->toBe('a=b&a=c');
});
