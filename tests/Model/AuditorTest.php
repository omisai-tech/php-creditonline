<?php

use Omisai\CreditOnline\Model\Address;
use Omisai\CreditOnline\Model\Auditor;

it('has correct model name', function () {
    $auditor = new Auditor();

    expect($auditor->getModelName())->toBe('Auditor');
});

it('declares correct open API types', function () {
    $types = Auditor::openAPITypes();

    expect($types)->toBe([
        'regnumber' => 'string',
        'name' => 'string',
        'address' => '\Omisai\CreditOnline\Model\Address',
        'start' => '\DateTime',
    ]);
});

it('declares correct open API formats', function () {
    $formats = Auditor::openAPIFormats();

    expect($formats)->toBe([
        'regnumber' => null,
        'name' => null,
        'address' => null,
        'start' => 'date',
    ]);
});

it('declares correct attribute map', function () {
    $map = Auditor::attributeMap();

    expect($map)->toBe([
        'regnumber' => 'Regnumber',
        'name' => 'Name',
        'address' => 'Address',
        'start' => 'Start',
    ]);
});

it('declares correct setters', function () {
    $setters = Auditor::setters();

    expect($setters)->toBe([
        'regnumber' => 'setRegnumber',
        'name' => 'setName',
        'address' => 'setAddress',
        'start' => 'setStart',
    ]);
});

it('declares correct getters', function () {
    $getters = Auditor::getters();

    expect($getters)->toBe([
        'regnumber' => 'getRegnumber',
        'name' => 'getName',
        'address' => 'getAddress',
        'start' => 'getStart',
    ]);
});

it('instantiates with empty constructor returning null property values', function () {
    $auditor = new Auditor();

    expect($auditor->getRegnumber())->toBeNull();
    expect($auditor->getName())->toBeNull();
    expect($auditor->getAddress())->toBeNull();
    expect($auditor->getStart())->toBeNull();
});

it('instantiates with data array setting property values', function () {
    $address = new Address();
    $start = new DateTime('2023-01-15');
    $auditor = new Auditor([
        'regnumber' => '01-09-123456',
        'name' => 'Test Auditor Kft.',
        'address' => $address,
        'start' => $start,
    ]);

    expect($auditor->getRegnumber())->toBe('01-09-123456');
    expect($auditor->getName())->toBe('Test Auditor Kft.');
    expect($auditor->getAddress())->toBe($address);
    expect($auditor->getStart())->toBe($start);
});

it('instantiates with partial data', function () {
    $auditor = new Auditor([
        'regnumber' => '01-09-123456',
        'name' => 'Test Auditor Kft.',
    ]);

    expect($auditor->getRegnumber())->toBe('01-09-123456');
    expect($auditor->getName())->toBe('Test Auditor Kft.');
    expect($auditor->getAddress())->toBeNull();
    expect($auditor->getStart())->toBeNull();
});

it('handles constructor with null argument', function () {
    $auditor = new Auditor(null);

    expect($auditor->getRegnumber())->toBeNull();
    expect($auditor->getName())->toBeNull();
    expect($auditor->getAddress())->toBeNull();
    expect($auditor->getStart())->toBeNull();
});

it('sets and gets regnumber', function (string $value) {
    $auditor = new Auditor();
    $result = $auditor->setRegnumber($value);

    expect($result)->toBeInstanceOf(Auditor::class);
    expect($auditor->getRegnumber())->toBe($value);
})->with([
    'regular' => '01-09-123456',
    'empty string' => '',
]);

it('sets and gets name', function (string $value) {
    $auditor = new Auditor();
    $result = $auditor->setName($value);

    expect($result)->toBeInstanceOf(Auditor::class);
    expect($auditor->getName())->toBe($value);
})->with([
    'regular' => 'Test Auditor Kft.',
    'empty string' => '',
]);

it('sets and gets address', function () {
    $auditor = new Auditor();
    $address = new Address();
    $result = $auditor->setAddress($address);

    expect($result)->toBeInstanceOf(Auditor::class);
    expect($auditor->getAddress())->toBe($address);
});

it('sets and gets start', function () {
    $auditor = new Auditor();
    $start = new DateTime('2023-01-15');
    $result = $auditor->setStart($start);

    expect($result)->toBeInstanceOf(Auditor::class);
    expect($auditor->getStart())->toBe($start);
});

it('throws exception when setting null on non-nullable property', function (string $property, string $setter, mixed $validValue) {
    $auditor = new Auditor();
    $auditor->{$setter}($validValue);

    expect(fn () => $auditor->{$setter}(null))->toThrow(
        \InvalidArgumentException::class,
        "non-nullable {$property} cannot be null"
    );
})->with([
    'regnumber' => ['regnumber', 'setRegnumber', '01-09-123456'],
    'name' => ['name', 'setName', 'Test Name'],
    'address' => ['address', 'setAddress', new Address()],
    'start' => ['start', 'setStart', new DateTime('2023-01-15')],
]);

it('declares no nullable properties', function () {
    expect(Auditor::isNullable('regnumber'))->toBeFalse();
    expect(Auditor::isNullable('name'))->toBeFalse();
    expect(Auditor::isNullable('address'))->toBeFalse();
    expect(Auditor::isNullable('start'))->toBeFalse();
    expect(Auditor::isNullable('nonexistent'))->toBeFalse();
});

it('returns false for isNullableSetToNull on non-nullable model', function () {
    $auditor = new Auditor();

    expect($auditor->isNullableSetToNull('regnumber'))->toBeFalse();
    expect($auditor->isNullableSetToNull('name'))->toBeFalse();
    expect($auditor->isNullableSetToNull('address'))->toBeFalse();
    expect($auditor->isNullableSetToNull('start'))->toBeFalse();
});

it('returns true for valid and empty invalid properties', function () {
    $auditor = new Auditor();

    expect($auditor->valid())->toBeTrue();
    expect($auditor->listInvalidProperties())->toBe([]);
});

it('valid with all properties set', function () {
    $auditor = new Auditor();
    $auditor->setRegnumber('01-09-123456');
    $auditor->setName('Test Auditor Kft.');
    $auditor->setAddress(new Address());
    $auditor->setStart(new DateTime());

    expect($auditor->valid())->toBeTrue();
    expect($auditor->listInvalidProperties())->toBe([]);
});

it('implements ArrayAccess offsetExists', function () {
    $auditor = new Auditor();

    expect($auditor->offsetExists('regnumber'))->toBeFalse();

    $auditor->setRegnumber('01-09-123456');
    expect($auditor->offsetExists('regnumber'))->toBeTrue();
    expect($auditor->offsetExists('nonexistent'))->toBeFalse();
});

it('implements ArrayAccess offsetGet', function () {
    $auditor = new Auditor();
    $auditor->setRegnumber('01-09-123456');
    $auditor->setName('Test Name');

    expect($auditor->offsetGet('regnumber'))->toBe('01-09-123456');
    expect($auditor->offsetGet('name'))->toBe('Test Name');
    expect($auditor->offsetGet('nonexistent'))->toBeNull();
});

it('implements ArrayAccess offsetSet', function () {
    $auditor = new Auditor();

    $auditor->offsetSet('regnumber', '01-09-123456');
    expect($auditor->getRegnumber())->toBe('01-09-123456');

    $auditor->offsetSet(null, 'appended_value');
    expect($auditor->offsetGet(0))->toBe('appended_value');
});

it('implements ArrayAccess offsetUnset', function () {
    $auditor = new Auditor();
    $auditor->setRegnumber('01-09-123456');
    expect($auditor->offsetExists('regnumber'))->toBeTrue();

    $auditor->offsetUnset('regnumber');
    expect($auditor->offsetExists('regnumber'))->toBeFalse();
});

it('serializes via jsonSerialize', function () {
    $auditor = new Auditor();
    $auditor->setRegnumber('01-09-123456');
    $auditor->setName('Test Auditor Kft.');

    $result = $auditor->jsonSerialize();

    expect($result)->toBeObject();
    expect($result->Regnumber)->toBe('01-09-123456');
    expect($result->Name)->toBe('Test Auditor Kft.');
});

it('returns string representation via __toString', function () {
    $auditor = new Auditor();
    $auditor->setRegnumber('01-09-123456');
    $auditor->setName('Test Auditor Kft.');

    $str = (string) $auditor;

    expect($str)->toBeString();
    expect(strlen($str))->toBeGreaterThan(0);

    $decoded = json_decode($str, true);
    expect($decoded)->toBeArray();
    expect($decoded['Regnumber'])->toBe('01-09-123456');
});

it('returns header-safe presentation via toHeaderValue', function () {
    $auditor = new Auditor();
    $auditor->setRegnumber('01-09-123456');

    $header = $auditor->toHeaderValue();

    expect($header)->toBeString();
    expect(strlen($header))->toBeGreaterThan(0);

    $decoded = json_decode($header, true);
    expect($decoded)->toBeArray();
    expect($decoded['Regnumber'])->toBe('01-09-123456');
});

it('supports chaining setters', function () {
    $auditor = new Auditor();
    $result = $auditor
        ->setRegnumber('01-09-123456')
        ->setName('Test Auditor Kft.')
        ->setAddress(new Address())
        ->setStart(new DateTime('2023-01-15'));

    expect($result)->toBeInstanceOf(Auditor::class);
    expect($auditor->getRegnumber())->toBe('01-09-123456');
    expect($auditor->getName())->toBe('Test Auditor Kft.');
    expect($auditor->getAddress())->toBeInstanceOf(Address::class);
    expect($auditor->getStart())->toBeInstanceOf(DateTime::class);
});
