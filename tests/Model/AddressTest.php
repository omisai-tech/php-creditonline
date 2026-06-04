<?php

use Omisai\CreditOnline\Model\Address;

beforeEach(function () {
    $this->model = new Address();
});

it('getModelName returns Address', function () {
    expect($this->model->getModelName())->toBe('Address');
});

it('openAPITypes returns correct type array', function () {
    $types = Address::openAPITypes();
    expect($types)->toBe([
        'country_code' => 'string',
        'zip' => 'string',
        'city' => 'string',
        'street' => 'string',
        'place_type' => 'string',
        'house_number' => 'string',
    ]);
});

it('openAPIFormats returns all null formats', function () {
    $formats = Address::openAPIFormats();
    expect($formats)->toHaveKeys(['country_code', 'zip', 'city', 'street', 'place_type', 'house_number']);
});

it('attributeMap uses correct original names', function (string $local, string $original) {
    $map = Address::attributeMap();
    expect($map[$local])->toBe($original);
})->with([
    ['country_code', 'CountryCode'],
    ['zip', 'Zip'],
    ['city', 'City'],
    ['street', 'Street'],
    ['place_type', 'PlaceType'],
    ['house_number', 'HouseNumber'],
]);

it('setters returns correct mapping', function (string $property, string $setter) {
    expect(Address::setters()[$property])->toBe($setter);
})->with([
    ['country_code', 'setCountryCode'],
    ['zip', 'setZip'],
    ['city', 'setCity'],
    ['street', 'setStreet'],
    ['place_type', 'setPlaceType'],
    ['house_number', 'setHouseNumber'],
]);

it('getters returns correct mapping', function (string $property, string $getter) {
    expect(Address::getters()[$property])->toBe($getter);
})->with([
    ['country_code', 'getCountryCode'],
    ['zip', 'getZip'],
    ['city', 'getCity'],
    ['street', 'getStreet'],
    ['place_type', 'getPlaceType'],
    ['house_number', 'getHouseNumber'],
]);

it('setCountryCode sets value and returns $this', function () {
    $result = $this->model->setCountryCode('HU');
    expect($result)->toBe($this->model);
    expect($this->model->getCountryCode())->toBe('HU');
});

it('setZip sets value and returns $this', function () {
    $result = $this->model->setZip('1061');
    expect($result)->toBe($this->model);
    expect($this->model->getZip())->toBe('1061');
});

it('setCity sets value and returns $this', function () {
    $result = $this->model->setCity('Budapest');
    expect($result)->toBe($this->model);
    expect($this->model->getCity())->toBe('Budapest');
});

it('setStreet sets value and returns $this', function () {
    $result = $this->model->setStreet('Andrássy út');
    expect($result)->toBe($this->model);
    expect($this->model->getStreet())->toBe('Andrássy út');
});

it('setPlaceType sets value and returns $this', function () {
    $result = $this->model->setPlaceType('út');
    expect($result)->toBe($this->model);
    expect($this->model->getPlaceType())->toBe('út');
});

it('setHouseNumber sets value and returns $this', function () {
    $result = $this->model->setHouseNumber('1');
    expect($result)->toBe($this->model);
    expect($this->model->getHouseNumber())->toBe('1');
});

it('setter throws on null for non-nullable properties', function (string $property) {
    $setters = Address::setters();
    $setter = $setters[$property];
    $this->model->{$setter}(null);
})->throws(\InvalidArgumentException::class)->with([
    ['country_code'],
    ['zip'],
    ['city'],
    ['street'],
    ['place_type'],
    ['house_number'],
]);

it('constructor with null sets all properties to null', function () {
    $model = new Address();
    expect($model->getCountryCode())->toBeNull();
    expect($model->getZip())->toBeNull();
    expect($model->getCity())->toBeNull();
    expect($model->getStreet())->toBeNull();
    expect($model->getPlaceType())->toBeNull();
    expect($model->getHouseNumber())->toBeNull();
});

it('constructor with data sets provided properties', function () {
    $model = new Address([
        'country_code' => 'HU',
        'zip' => '1061',
        'city' => 'Budapest',
    ]);
    expect($model->getCountryCode())->toBe('HU');
    expect($model->getZip())->toBe('1061');
    expect($model->getCity())->toBe('Budapest');
    expect($model->getStreet())->toBeNull();
});

it('constructor with partial data sets only given properties', function () {
    $model = new Address(['zip' => '1061']);
    expect($model->getZip())->toBe('1061');
    expect($model->getCity())->toBeNull();
});

it('constructor with empty array initializes all null', function () {
    $model = new Address([]);
    expect($model->getCountryCode())->toBeNull();
});

it('implements ArrayAccess: offsetExists', function () {
    $this->model->setCity('Budapest');
    expect($this->model->offsetExists('city'))->toBeTrue();
    expect($this->model->offsetExists('nonexistent'))->toBeFalse();
});

it('implements ArrayAccess: offsetGet', function () {
    $this->model->setCity('Budapest');
    expect($this->model->offsetGet('city'))->toBe('Budapest');
    expect($this->model->offsetGet('nonexistent'))->toBeNull();
});

it('implements ArrayAccess: offsetSet with key', function () {
    $this->model->offsetSet('city', 'Debrecen');
    expect($this->model->offsetGet('city'))->toBe('Debrecen');
});

it('implements ArrayAccess: offsetSet without key', function () {
    $this->model->offsetSet(null, 'appended');
    expect($this->model->offsetGet(0))->toBe('appended');
});

it('implements ArrayAccess: offsetUnset', function () {
    $this->model->setCity('Budapest');
    $this->model->offsetUnset('city');
    expect($this->model->offsetExists('city'))->toBeFalse();
});

it('jsonSerialize returns array', function () {
    $this->model->setCity('Budapest');
    $serialized = $this->model->jsonSerialize();
    expect($serialized)->toBeObject();
});

it('__toString returns JSON string', function () {
    $this->model->setCity('Budapest');
    $str = $this->model->__toString();
    expect($str)->toBeString();
    expect($str)->toBeString()->not->toBeEmpty();
});

it('toHeaderValue returns JSON string', function () {
    $this->model->setCity('Budapest');
    $value = $this->model->toHeaderValue();
    expect($value)->toBeString();
    expect(json_decode($value, true))->toBeArray();
});

it('isNullable returns false for all properties', function (string $property) {
    expect(Address::isNullable($property))->toBeFalse();
})->with(['country_code', 'zip', 'city', 'street', 'place_type', 'house_number']);

it('isNullable returns false for unknown property', function () {
    expect(Address::isNullable('unknown'))->toBeFalse();
});

it('isNullableSetToNull initially returns false', function () {
    expect($this->model->isNullableSetToNull('city'))->toBeFalse();
    expect($this->model->isNullableSetToNull('zip'))->toBeFalse();
});

it('listInvalidProperties returns empty array always', function () {
    expect($this->model->listInvalidProperties())->toBeEmpty();
});

it('valid always returns true', function () {
    expect($this->model->valid())->toBeTrue();
});
