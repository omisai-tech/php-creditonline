<?php

use Omisai\CreditOnline\Model\Address;
use Omisai\CreditOnline\Model\Member;

it('has correct model name', function () {
    $member = new Member();

    expect($member->getModelName())->toBe('Member');
});

it('declares correct open API types', function () {
    $types = Member::openAPITypes();

    expect($types)->toBe([
        'regnumber' => 'string',
        'name' => 'string',
        'mother_name' => 'string',
        'address' => '\Omisai\CreditOnline\Model\Address',
        'start' => '\DateTime',
    ]);
});

it('declares correct open API formats', function () {
    $formats = Member::openAPIFormats();

    expect($formats)->toBe([
        'regnumber' => null,
        'name' => null,
        'mother_name' => null,
        'address' => null,
        'start' => 'date',
    ]);
});

it('declares correct attribute map', function () {
    $map = Member::attributeMap();

    expect($map)->toBe([
        'regnumber' => 'Regnumber',
        'name' => 'Name',
        'mother_name' => 'MotherName',
        'address' => 'Address',
        'start' => 'Start',
    ]);
});

it('declares correct setters', function () {
    $setters = Member::setters();

    expect($setters)->toBe([
        'regnumber' => 'setRegnumber',
        'name' => 'setName',
        'mother_name' => 'setMotherName',
        'address' => 'setAddress',
        'start' => 'setStart',
    ]);
});

it('declares correct getters', function () {
    $getters = Member::getters();

    expect($getters)->toBe([
        'regnumber' => 'getRegnumber',
        'name' => 'getName',
        'mother_name' => 'getMotherName',
        'address' => 'getAddress',
        'start' => 'getStart',
    ]);
});

it('instantiates with empty constructor returning null property values', function () {
    $member = new Member();

    expect($member->getRegnumber())->toBeNull();
    expect($member->getName())->toBeNull();
    expect($member->getMotherName())->toBeNull();
    expect($member->getAddress())->toBeNull();
    expect($member->getStart())->toBeNull();
});

it('instantiates with data array setting property values', function () {
    $address = new Address();
    $start = new DateTime('2023-06-01');
    $member = new Member([
        'regnumber' => '01-09-123456',
        'name' => 'Kovács János',
        'mother_name' => 'Nagy Mária',
        'address' => $address,
        'start' => $start,
    ]);

    expect($member->getRegnumber())->toBe('01-09-123456');
    expect($member->getName())->toBe('Kovács János');
    expect($member->getMotherName())->toBe('Nagy Mária');
    expect($member->getAddress())->toBe($address);
    expect($member->getStart())->toBe($start);
});

it('instantiates with partial data', function () {
    $member = new Member([
        'regnumber' => '01-09-123456',
        'name' => 'Kovács János',
    ]);

    expect($member->getRegnumber())->toBe('01-09-123456');
    expect($member->getName())->toBe('Kovács János');
    expect($member->getMotherName())->toBeNull();
    expect($member->getAddress())->toBeNull();
    expect($member->getStart())->toBeNull();
});

it('handles constructor with null argument', function () {
    $member = new Member(null);

    expect($member->getRegnumber())->toBeNull();
    expect($member->getName())->toBeNull();
    expect($member->getMotherName())->toBeNull();
    expect($member->getAddress())->toBeNull();
    expect($member->getStart())->toBeNull();
});

it('sets and gets regnumber', function (string $value) {
    $member = new Member();
    $result = $member->setRegnumber($value);

    expect($result)->toBeInstanceOf(Member::class);
    expect($member->getRegnumber())->toBe($value);
})->with([
    'regular' => '01-09-123456',
    'empty' => '',
]);

it('sets and gets name', function (string $value) {
    $member = new Member();
    $result = $member->setName($value);

    expect($result)->toBeInstanceOf(Member::class);
    expect($member->getName())->toBe($value);
})->with([
    'regular' => 'Kovács János',
    'empty' => '',
]);

it('sets and gets mother_name', function (string $value) {
    $member = new Member();
    $result = $member->setMotherName($value);

    expect($result)->toBeInstanceOf(Member::class);
    expect($member->getMotherName())->toBe($value);
})->with([
    'regular' => 'Nagy Mária',
    'empty' => '',
]);

it('sets and gets address', function () {
    $member = new Member();
    $address = new Address();
    $result = $member->setAddress($address);

    expect($result)->toBeInstanceOf(Member::class);
    expect($member->getAddress())->toBe($address);
});

it('sets and gets start', function () {
    $member = new Member();
    $start = new DateTime('2023-06-01');
    $result = $member->setStart($start);

    expect($result)->toBeInstanceOf(Member::class);
    expect($member->getStart())->toBe($start);
});

it('throws exception when setting null on non-nullable property', function (string $property, string $setter, mixed $validValue) {
    $member = new Member();
    $member->{$setter}($validValue);

    expect(fn () => $member->{$setter}(null))->toThrow(
        \InvalidArgumentException::class,
        "non-nullable {$property} cannot be null"
    );
})->with([
    'regnumber' => ['regnumber', 'setRegnumber', '01-09-123456'],
    'name' => ['name', 'setName', 'Test Name'],
    'mother_name' => ['mother_name', 'setMotherName', 'Test Mother'],
    'address' => ['address', 'setAddress', new Address()],
    'start' => ['start', 'setStart', new DateTime('2023-06-01')],
]);

it('declares no nullable properties', function () {
    expect(Member::isNullable('regnumber'))->toBeFalse();
    expect(Member::isNullable('name'))->toBeFalse();
    expect(Member::isNullable('mother_name'))->toBeFalse();
    expect(Member::isNullable('address'))->toBeFalse();
    expect(Member::isNullable('start'))->toBeFalse();
    expect(Member::isNullable('nonexistent'))->toBeFalse();
});

it('returns false for isNullableSetToNull on non-nullable model', function () {
    $member = new Member();

    expect($member->isNullableSetToNull('regnumber'))->toBeFalse();
    expect($member->isNullableSetToNull('name'))->toBeFalse();
    expect($member->isNullableSetToNull('mother_name'))->toBeFalse();
    expect($member->isNullableSetToNull('address'))->toBeFalse();
    expect($member->isNullableSetToNull('start'))->toBeFalse();
});

it('returns true for valid and empty invalid properties', function () {
    $member = new Member();

    expect($member->valid())->toBeTrue();
    expect($member->listInvalidProperties())->toBe([]);
});

it('valid with all properties set', function () {
    $member = new Member();
    $member->setRegnumber('01-09-123456');
    $member->setName('Kovács János');
    $member->setMotherName('Nagy Mária');
    $member->setAddress(new Address());
    $member->setStart(new DateTime());

    expect($member->valid())->toBeTrue();
    expect($member->listInvalidProperties())->toBe([]);
});

it('implements ArrayAccess offsetExists', function () {
    $member = new Member();

    expect($member->offsetExists('regnumber'))->toBeFalse();

    $member->setRegnumber('01-09-123456');
    expect($member->offsetExists('regnumber'))->toBeTrue();
    expect($member->offsetExists('mother_name'))->toBeFalse();
    expect($member->offsetExists('nonexistent'))->toBeFalse();
});

it('implements ArrayAccess offsetGet', function () {
    $member = new Member();
    $member->setRegnumber('01-09-123456');
    $member->setMotherName('Nagy Mária');

    expect($member->offsetGet('regnumber'))->toBe('01-09-123456');
    expect($member->offsetGet('mother_name'))->toBe('Nagy Mária');
    expect($member->offsetGet('nonexistent'))->toBeNull();
});

it('implements ArrayAccess offsetSet', function () {
    $member = new Member();

    $member->offsetSet('mother_name', 'Nagy Mária');
    expect($member->getMotherName())->toBe('Nagy Mária');

    $member->offsetSet(null, 'appended_value');
    expect($member->offsetGet(0))->toBe('appended_value');
});

it('implements ArrayAccess offsetUnset', function () {
    $member = new Member();
    $member->setRegnumber('01-09-123456');
    expect($member->offsetExists('regnumber'))->toBeTrue();

    $member->offsetUnset('regnumber');
    expect($member->offsetExists('regnumber'))->toBeFalse();
});

it('serializes via jsonSerialize', function () {
    $member = new Member();
    $member->setRegnumber('01-09-123456');
    $member->setName('Kovács János');
    $member->setMotherName('Nagy Mária');

    $result = $member->jsonSerialize();

    expect($result)->toBeObject();
    expect($result->Regnumber)->toBe('01-09-123456');
    expect($result->Name)->toBe('Kovács János');
    expect($result->MotherName)->toBe('Nagy Mária');
});

it('returns string representation via __toString', function () {
    $member = new Member();
    $member->setRegnumber('01-09-123456');
    $member->setName('Kovács János');

    $str = (string) $member;

    expect($str)->toBeString();
    expect(strlen($str))->toBeGreaterThan(0);

    $decoded = json_decode($str, true);
    expect($decoded)->toBeArray();
    expect($decoded['Regnumber'])->toBe('01-09-123456');
});

it('returns header-safe presentation via toHeaderValue', function () {
    $member = new Member();
    $member->setRegnumber('01-09-123456');

    $header = $member->toHeaderValue();

    expect($header)->toBeString();
    expect(strlen($header))->toBeGreaterThan(0);

    $decoded = json_decode($header, true);
    expect($decoded)->toBeArray();
    expect($decoded['Regnumber'])->toBe('01-09-123456');
});

it('supports chaining setters', function () {
    $member = new Member();
    $result = $member
        ->setRegnumber('01-09-123456')
        ->setName('Kovács János')
        ->setMotherName('Nagy Mária')
        ->setAddress(new Address())
        ->setStart(new DateTime('2023-06-01'));

    expect($result)->toBeInstanceOf(Member::class);
    expect($member->getRegnumber())->toBe('01-09-123456');
    expect($member->getName())->toBe('Kovács János');
    expect($member->getMotherName())->toBe('Nagy Mária');
    expect($member->getAddress())->toBeInstanceOf(Address::class);
    expect($member->getStart())->toBeInstanceOf(DateTime::class);
});
