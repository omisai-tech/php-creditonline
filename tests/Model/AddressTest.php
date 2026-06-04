<?php

use Omisai\CreditOnline\Model\Address;

$addressProperties = [
    'country_code',
    'zip',
    'city',
    'street',
    'place_type',
    'house_number',
];

it('returns the model name', function () {
    $model = new Address();
    expect($model->getModelName())->toBe('Address');
});

it('has correct openAPITypes', function () use ($addressProperties) {
    $types = Address::openAPITypes();
    expect($types)->toBeArray()->toHaveCount(count($addressProperties));
    foreach ($addressProperties as $prop) {
        expect($types[$prop])->toBe('string');
    }
});

it('has correct openAPIFormats', function () use ($addressProperties) {
    $formats = Address::openAPIFormats();
    expect($formats)->toBeArray()->toHaveCount(count($addressProperties));
    foreach ($addressProperties as $prop) {
        expect($formats[$prop])->toBeNull();
    }
});

it('has correct attributeMap', function () {
    $map = Address::attributeMap();
    expect($map)->toBeArray();
    expect($map['country_code'])->toBe('CountryCode');
    expect($map['zip'])->toBe('Zip');
    expect($map['city'])->toBe('City');
    expect($map['street'])->toBe('Street');
    expect($map['place_type'])->toBe('PlaceType');
    expect($map['house_number'])->toBe('HouseNumber');
});

it('has correct setters mapping', function () {
    $setters = Address::setters();
    expect($setters)->toBeArray();
    expect($setters['country_code'])->toBe('setCountryCode');
    expect($setters['house_number'])->toBe('setHouseNumber');
});

it('has correct getters mapping', function () {
    $getters = Address::getters();
    expect($getters)->toBeArray();
    expect($getters['country_code'])->toBe('getCountryCode');
    expect($getters['house_number'])->toBe('getHouseNumber');
});

it('defaults all properties to null on construction with no data', function () use ($addressProperties) {
    $model = new Address();
    foreach ($addressProperties as $prop) {
        $method = 'get' . str_replace('_', '', ucwords($prop, '_'));
        expect($model->$method())->toBeNull();
    }
});

it('defaults all properties to null on construction with null', function () use ($addressProperties) {
    $model = new Address(null);
    foreach ($addressProperties as $prop) {
        $method = 'get' . str_replace('_', '', ucwords($prop, '_'));
        expect($model->$method())->toBeNull();
    }
});

it('defaults all properties to null on construction with empty array', function () use ($addressProperties) {
    $model = new Address([]);
    foreach ($addressProperties as $prop) {
        $method = 'get' . str_replace('_', '', ucwords($prop, '_'));
        expect($model->$method())->toBeNull();
    }
});

it('sets properties from construction data array', function () {
    $model = new Address([
        'country_code' => 'HU',
        'zip' => '1055',
        'city' => 'Budapest',
        'street' => 'Fő utca',
        'place_type' => 'utca',
        'house_number' => '1',
    ]);
    expect($model->getCountryCode())->toBe('HU');
    expect($model->getZip())->toBe('1055');
    expect($model->getCity())->toBe('Budapest');
    expect($model->getStreet())->toBe('Fő utca');
    expect($model->getPlaceType())->toBe('utca');
    expect($model->getHouseNumber())->toBe('1');
});

it('sets partial properties from construction data', function () {
    $model = new Address([
        'country_code' => 'HU',
        'city' => 'Budapest',
    ]);
    expect($model->getCountryCode())->toBe('HU');
    expect($model->getCity())->toBe('Budapest');
    expect($model->getZip())->toBeNull();
    expect($model->getStreet())->toBeNull();
    expect($model->getPlaceType())->toBeNull();
    expect($model->getHouseNumber())->toBeNull();
});

it('getters and setters work correctly', function (string $property) {
    $model = new Address();
    $setter = 'set' . str_replace('_', '', ucwords($property, '_'));
    $getter = 'get' . str_replace('_', '', ucwords($property, '_'));
    $result = $model->$setter('test_value');
    expect($result)->toBe($model);
    expect($model->$getter())->toBe('test_value');
})->with($addressProperties);

it('setters can set empty string', function (string $property) {
    $model = new Address();
    $setter = 'set' . str_replace('_', '', ucwords($property, '_'));
    $getter = 'get' . str_replace('_', '', ucwords($property, '_'));
    $model->$setter('');
    expect($model->$getter())->toBe('');
})->with($addressProperties);

it('non-nullable setters throw on null', function (string $property) {
    $model = new Address();
    $setter = 'set' . str_replace('_', '', ucwords($property, '_'));
    $model->$setter(null);
})->with($addressProperties)->throws(\InvalidArgumentException::class);

it('listInvalidProperties returns empty array (no required fields)', function () {
    $model = new Address();
    expect($model->listInvalidProperties())->toBe([]);
});

it('valid returns true for default state', function () {
    $model = new Address();
    expect($model->valid())->toBeTrue();
});

it('valid returns true after setting properties', function () {
    $model = new Address(['city' => 'Budapest']);
    expect($model->valid())->toBeTrue();
});

it('implements ArrayAccess: offsetExists', function () {
    $model = new Address(['city' => 'Budapest']);
    expect($model->offsetExists('city'))->toBeTrue();
    expect($model->offsetExists('nonexistent'))->toBeFalse();
});

it('implements ArrayAccess: offsetGet', function () {
    $model = new Address(['city' => 'Budapest']);
    expect($model->offsetGet('city'))->toBe('Budapest');
    expect($model->offsetGet('nonexistent'))->toBeNull();
});

it('implements ArrayAccess: offsetSet with key', function () {
    $model = new Address();
    $model->offsetSet('city', 'Debrecen');
    expect($model->offsetGet('city'))->toBe('Debrecen');
});

it('implements ArrayAccess: offsetSet without key (append)', function () {
    $model = new Address();
    $model->offsetSet(null, 'extra_value');
    expect($model->offsetGet(0))->toBe('extra_value');
});

it('implements ArrayAccess: offsetUnset', function () {
    $model = new Address(['city' => 'Budapest']);
    $model->offsetUnset('city');
    expect($model->offsetExists('city'))->toBeFalse();
});

it('jsonSerialize returns object with PascalCase keys', function () {
    $model = new Address(['city' => 'Budapest', 'country_code' => 'HU']);
    $result = $model->jsonSerialize();
    expect($result)->toBeObject();
    expect($result->City)->toBe('Budapest');
    expect($result->CountryCode)->toBe('HU');
});

it('jsonSerialize omits null properties', function () {
    $model = new Address(['city' => 'Budapest']);
    $result = $model->jsonSerialize();
    expect(property_exists($result, 'City'))->toBeTrue();
    expect(property_exists($result, 'Zip'))->toBeFalse();
});

it('__toString returns JSON string with PascalCase keys', function () {
    $model = new Address(['city' => 'Budapest']);
    $str = (string) $model;
    expect($str)->toBeString();
    $decoded = json_decode($str, true);
    expect($decoded['City'])->toBe('Budapest');
});

it('toHeaderValue returns compact JSON', function () {
    $model = new Address(['city' => 'Budapest']);
    $value = $model->toHeaderValue();
    expect($value)->toBeString();
    expect(str_contains($value, "\n"))->toBeFalse();
});

it('isNullable returns false for all properties', function (string $property) {
    expect(Address::isNullable($property))->toBeFalse();
})->with($addressProperties);

it('isNullable returns false for unknown property', function () {
    expect(Address::isNullable('unknown'))->toBeFalse();
});

it('isNullableSetToNull returns false for set properties', function (string $property) {
    $model = new Address([$property => 'some_value']);
    expect($model->isNullableSetToNull($property))->toBeFalse();
})->with($addressProperties);
