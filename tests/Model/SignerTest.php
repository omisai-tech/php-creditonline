<?php

use Omisai\CreditOnline\Model\Address;
use Omisai\CreditOnline\Model\Signer;

it('has correct model name', function () {
    $signer = new Signer();

    expect($signer->getModelName())->toBe('Signer');
});

it('declares correct open API types', function () {
    $types = Signer::openAPITypes();

    expect($types)->toBe([
        'regnumber' => 'string',
        'name' => 'string',
        'mother_name' => 'string',
        'address' => '\Omisai\CreditOnline\Model\Address',
        'start' => '\DateTime',
    ]);
});

it('declares correct open API formats', function () {
    $formats = Signer::openAPIFormats();

    expect($formats)->toBe([
        'regnumber' => null,
        'name' => null,
        'mother_name' => null,
        'address' => null,
        'start' => 'date',
    ]);
});

it('declares correct attribute map', function () {
    $map = Signer::attributeMap();

    expect($map)->toBe([
        'regnumber' => 'Regnumber',
        'name' => 'Name',
        'mother_name' => 'MotherName',
        'address' => 'Address',
        'start' => 'Start',
    ]);
});

it('declares correct setters', function () {
    $setters = Signer::setters();

    expect($setters)->toBe([
        'regnumber' => 'setRegnumber',
        'name' => 'setName',
        'mother_name' => 'setMotherName',
        'address' => 'setAddress',
        'start' => 'setStart',
    ]);
});

it('declares correct getters', function () {
    $getters = Signer::getters();

    expect($getters)->toBe([
        'regnumber' => 'getRegnumber',
        'name' => 'getName',
        'mother_name' => 'getMotherName',
        'address' => 'getAddress',
        'start' => 'getStart',
    ]);
});

it('instantiates with empty constructor returning null property values', function () {
    $signer = new Signer();

    expect($signer->getRegnumber())->toBeNull();
    expect($signer->getName())->toBeNull();
    expect($signer->getMotherName())->toBeNull();
    expect($signer->getAddress())->toBeNull();
    expect($signer->getStart())->toBeNull();
});

it('instantiates with data array setting property values', function () {
    $address = new Address();
    $start = new DateTime('2024-01-10');
    $signer = new Signer([
        'regnumber' => '01-09-654321',
        'name' => 'Tóth András',
        'mother_name' => 'Szabó Ilona',
        'address' => $address,
        'start' => $start,
    ]);

    expect($signer->getRegnumber())->toBe('01-09-654321');
    expect($signer->getName())->toBe('Tóth András');
    expect($signer->getMotherName())->toBe('Szabó Ilona');
    expect($signer->getAddress())->toBe($address);
    expect($signer->getStart())->toBe($start);
});

it('instantiates with partial data', function () {
    $signer = new Signer([
        'regnumber' => '01-09-654321',
        'name' => 'Tóth András',
    ]);

    expect($signer->getRegnumber())->toBe('01-09-654321');
    expect($signer->getName())->toBe('Tóth András');
    expect($signer->getMotherName())->toBeNull();
    expect($signer->getAddress())->toBeNull();
    expect($signer->getStart())->toBeNull();
});

it('handles constructor with null argument', function () {
    $signer = new Signer(null);

    expect($signer->getRegnumber())->toBeNull();
    expect($signer->getName())->toBeNull();
    expect($signer->getMotherName())->toBeNull();
    expect($signer->getAddress())->toBeNull();
    expect($signer->getStart())->toBeNull();
});

it('sets and gets regnumber', function (string $value) {
    $signer = new Signer();
    $result = $signer->setRegnumber($value);

    expect($result)->toBeInstanceOf(Signer::class);
    expect($signer->getRegnumber())->toBe($value);
})->with([
    'regular' => '01-09-654321',
    'empty' => '',
]);

it('sets and gets name', function (string $value) {
    $signer = new Signer();
    $result = $signer->setName($value);

    expect($result)->toBeInstanceOf(Signer::class);
    expect($signer->getName())->toBe($value);
})->with([
    'regular' => 'Tóth András',
    'empty' => '',
]);

it('sets and gets mother_name', function (string $value) {
    $signer = new Signer();
    $result = $signer->setMotherName($value);

    expect($result)->toBeInstanceOf(Signer::class);
    expect($signer->getMotherName())->toBe($value);
})->with([
    'regular' => 'Szabó Ilona',
    'empty' => '',
]);

it('sets and gets address', function () {
    $signer = new Signer();
    $address = new Address();
    $result = $signer->setAddress($address);

    expect($result)->toBeInstanceOf(Signer::class);
    expect($signer->getAddress())->toBe($address);
});

it('sets and gets start', function () {
    $signer = new Signer();
    $start = new DateTime('2024-01-10');
    $result = $signer->setStart($start);

    expect($result)->toBeInstanceOf(Signer::class);
    expect($signer->getStart())->toBe($start);
});

it('throws exception when setting null on non-nullable property', function (string $property, string $setter, mixed $validValue) {
    $signer = new Signer();
    $signer->{$setter}($validValue);

    expect(fn () => $signer->{$setter}(null))->toThrow(
        \InvalidArgumentException::class,
        "non-nullable {$property} cannot be null"
    );
})->with([
    'regnumber' => ['regnumber', 'setRegnumber', '01-09-654321'],
    'name' => ['name', 'setName', 'Test Name'],
    'mother_name' => ['mother_name', 'setMotherName', 'Test Mother'],
    'address' => ['address', 'setAddress', new Address()],
    'start' => ['start', 'setStart', new DateTime('2024-01-10')],
]);

it('declares no nullable properties', function () {
    expect(Signer::isNullable('regnumber'))->toBeFalse();
    expect(Signer::isNullable('name'))->toBeFalse();
    expect(Signer::isNullable('mother_name'))->toBeFalse();
    expect(Signer::isNullable('address'))->toBeFalse();
    expect(Signer::isNullable('start'))->toBeFalse();
    expect(Signer::isNullable('nonexistent'))->toBeFalse();
});

it('returns false for isNullableSetToNull on non-nullable model', function () {
    $signer = new Signer();

    expect($signer->isNullableSetToNull('regnumber'))->toBeFalse();
    expect($signer->isNullableSetToNull('name'))->toBeFalse();
    expect($signer->isNullableSetToNull('mother_name'))->toBeFalse();
    expect($signer->isNullableSetToNull('address'))->toBeFalse();
    expect($signer->isNullableSetToNull('start'))->toBeFalse();
});

it('returns true for valid and empty invalid properties', function () {
    $signer = new Signer();

    expect($signer->valid())->toBeTrue();
    expect($signer->listInvalidProperties())->toBe([]);
});

it('valid with all properties set', function () {
    $signer = new Signer();
    $signer->setRegnumber('01-09-654321');
    $signer->setName('Tóth András');
    $signer->setMotherName('Szabó Ilona');
    $signer->setAddress(new Address());
    $signer->setStart(new DateTime());

    expect($signer->valid())->toBeTrue();
    expect($signer->listInvalidProperties())->toBe([]);
});

it('implements ArrayAccess offsetExists', function () {
    $signer = new Signer();

    expect($signer->offsetExists('regnumber'))->toBeFalse();

    $signer->setRegnumber('01-09-654321');
    expect($signer->offsetExists('regnumber'))->toBeTrue();
    expect($signer->offsetExists('name'))->toBeFalse();
    expect($signer->offsetExists('nonexistent'))->toBeFalse();
});

it('implements ArrayAccess offsetGet', function () {
    $signer = new Signer();
    $signer->setRegnumber('01-09-654321');
    $signer->setMotherName('Szabó Ilona');

    expect($signer->offsetGet('regnumber'))->toBe('01-09-654321');
    expect($signer->offsetGet('mother_name'))->toBe('Szabó Ilona');
    expect($signer->offsetGet('nonexistent'))->toBeNull();
});

it('implements ArrayAccess offsetSet', function () {
    $signer = new Signer();

    $signer->offsetSet('name', 'Tóth András');
    expect($signer->getName())->toBe('Tóth András');

    $signer->offsetSet(null, 'appended_value');
    expect($signer->offsetGet(0))->toBe('appended_value');
});

it('implements ArrayAccess offsetUnset', function () {
    $signer = new Signer();
    $signer->setRegnumber('01-09-654321');
    expect($signer->offsetExists('regnumber'))->toBeTrue();

    $signer->offsetUnset('regnumber');
    expect($signer->offsetExists('regnumber'))->toBeFalse();
});

it('serializes via jsonSerialize', function () {
    $signer = new Signer();
    $signer->setRegnumber('01-09-654321');
    $signer->setName('Tóth András');
    $signer->setMotherName('Szabó Ilona');

    $result = $signer->jsonSerialize();

    expect($result)->toBeObject();
    expect($result->Regnumber)->toBe('01-09-654321');
    expect($result->Name)->toBe('Tóth András');
    expect($result->MotherName)->toBe('Szabó Ilona');
});

it('returns string representation via __toString', function () {
    $signer = new Signer();
    $signer->setRegnumber('01-09-654321');
    $signer->setName('Tóth András');

    $str = (string) $signer;

    expect($str)->toBeString();
    expect(strlen($str))->toBeGreaterThan(0);

    $decoded = json_decode($str, true);
    expect($decoded)->toBeArray();
    expect($decoded['Regnumber'])->toBe('01-09-654321');
});

it('returns header-safe presentation via toHeaderValue', function () {
    $signer = new Signer();
    $signer->setRegnumber('01-09-654321');

    $header = $signer->toHeaderValue();

    expect($header)->toBeString();
    expect(strlen($header))->toBeGreaterThan(0);

    $decoded = json_decode($header, true);
    expect($decoded)->toBeArray();
    expect($decoded['Regnumber'])->toBe('01-09-654321');
});

it('supports chaining setters', function () {
    $signer = new Signer();
    $result = $signer
        ->setRegnumber('01-09-654321')
        ->setName('Tóth András')
        ->setMotherName('Szabó Ilona')
        ->setAddress(new Address())
        ->setStart(new DateTime('2024-01-10'));

    expect($result)->toBeInstanceOf(Signer::class);
    expect($signer->getRegnumber())->toBe('01-09-654321');
    expect($signer->getName())->toBe('Tóth András');
    expect($signer->getMotherName())->toBe('Szabó Ilona');
    expect($signer->getAddress())->toBeInstanceOf(Address::class);
    expect($signer->getStart())->toBeInstanceOf(DateTime::class);
});
