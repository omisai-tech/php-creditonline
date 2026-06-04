<?php

use Omisai\CreditOnline\Model\Auditor;
use Omisai\CreditOnline\Model\Address;

beforeEach(function () {
    $this->model = new Auditor();
});

it('getModelName returns Auditor', function () {
    expect($this->model->getModelName())->toBe('Auditor');
});

it('openAPITypes returns correct type array', function () {
    $types = Auditor::openAPITypes();
    expect($types)->toBe([
        'regnumber' => 'string',
        'name' => 'string',
        'address' => '\Omisai\CreditOnline\Model\Address',
        'start' => '\DateTime',
    ]);
});

it('openAPIFormats has date format for start', function () {
    $formats = Auditor::openAPIFormats();
    expect($formats)->toHaveKeys(['regnumber', 'name', 'address', 'start']);
    expect($formats['start'])->toBe('date');
});

it('attributeMap uses correct original names', function (string $local, string $original) {
    $map = Auditor::attributeMap();
    expect($map[$local])->toBe($original);
})->with([
    ['regnumber', 'Regnumber'],
    ['name', 'Name'],
    ['address', 'Address'],
    ['start', 'Start'],
]);

it('setters returns correct mapping', function (string $property, string $setter) {
    expect(Auditor::setters()[$property])->toBe($setter);
})->with([
    ['regnumber', 'setRegnumber'],
    ['name', 'setName'],
    ['address', 'setAddress'],
    ['start', 'setStart'],
]);

it('getters returns correct mapping', function (string $property, string $getter) {
    expect(Auditor::getters()[$property])->toBe($getter);
})->with([
    ['regnumber', 'getRegnumber'],
    ['name', 'getName'],
    ['address', 'getAddress'],
    ['start', 'getStart'],
]);

it('setRegnumber sets value and returns $this', function () {
    $result = $this->model->setRegnumber('01-09-123456');
    expect($result)->toBe($this->model);
    expect($this->model->getRegnumber())->toBe('01-09-123456');
});

it('setName sets value and returns $this', function () {
    $result = $this->model->setName('John Doe');
    expect($result)->toBe($this->model);
    expect($this->model->getName())->toBe('John Doe');
});

it('setAddress sets Address value and returns $this', function () {
    $address = new Address();
    $result = $this->model->setAddress($address);
    expect($result)->toBe($this->model);
    expect($this->model->getAddress())->toBe($address);
});

it('setStart sets DateTime value and returns $this', function () {
    $date = new DateTime('2023-01-15');
    $result = $this->model->setStart($date);
    expect($result)->toBe($this->model);
    expect($this->model->getStart())->toBe($date);
});

it('setter throws on null for non-nullable properties', function (string $property) {
    $setters = Auditor::setters();
    $setter = $setters[$property];
    $this->model->{$setter}(null);
})->throws(\InvalidArgumentException::class)->with([
    ['regnumber'],
    ['name'],
    ['address'],
    ['start'],
]);

it('constructor with null sets all properties to null', function () {
    $model = new Auditor();
    expect($model->getRegnumber())->toBeNull();
    expect($model->getName())->toBeNull();
    expect($model->getAddress())->toBeNull();
    expect($model->getStart())->toBeNull();
});

it('constructor with data sets provided properties', function () {
    $address = new Address();
    $date = new DateTime('2023-01-15');
    $model = new Auditor([
        'regnumber' => '01-09-123456',
        'name' => 'John Doe',
        'address' => $address,
        'start' => $date,
    ]);
    expect($model->getRegnumber())->toBe('01-09-123456');
    expect($model->getName())->toBe('John Doe');
    expect($model->getAddress())->toBe($address);
    expect($model->getStart())->toBe($date);
});

it('constructor with partial data leaves others null', function () {
    $model = new Auditor(['name' => 'John Doe']);
    expect($model->getName())->toBe('John Doe');
    expect($model->getRegnumber())->toBeNull();
});

it('constructor with empty array initializes all null', function () {
    $model = new Auditor([]);
    expect($model->getRegnumber())->toBeNull();
});

it('implements ArrayAccess: offsetExists', function () {
    $this->model->setName('John');
    expect($this->model->offsetExists('name'))->toBeTrue();
    expect($this->model->offsetExists('nonexistent'))->toBeFalse();
});

it('implements ArrayAccess: offsetGet', function () {
    $this->model->setName('John');
    expect($this->model->offsetGet('name'))->toBe('John');
    expect($this->model->offsetGet('nonexistent'))->toBeNull();
});

it('implements ArrayAccess: offsetSet with key', function () {
    $this->model->offsetSet('name', 'Jane');
    expect($this->model->offsetGet('name'))->toBe('Jane');
});

it('implements ArrayAccess: offsetSet without key', function () {
    $this->model->offsetSet(null, 'value');
    expect($this->model->offsetGet(0))->toBe('value');
});

it('implements ArrayAccess: offsetUnset', function () {
    $this->model->setName('John');
    $this->model->offsetUnset('name');
    expect($this->model->offsetExists('name'))->toBeFalse();
});

it('jsonSerialize returns array', function () {
    $this->model->setName('John');
    $serialized = $this->model->jsonSerialize();
    expect($serialized)->toBeObject();
});

it('__toString returns JSON string', function () {
    $this->model->setName('John');
    $str = $this->model->__toString();
    expect($str)->toBeString();
    expect($str)->toBeString()->not->toBeEmpty();
});

it('toHeaderValue returns JSON string', function () {
    $this->model->setName('John');
    $value = $this->model->toHeaderValue();
    expect($value)->toBeString();
    expect(json_decode($value, true))->toBeArray();
});

it('isNullable returns false for all properties', function (string $property) {
    expect(Auditor::isNullable($property))->toBeFalse();
})->with(['regnumber', 'name', 'address', 'start']);

it('isNullable returns false for unknown property', function () {
    expect(Auditor::isNullable('unknown'))->toBeFalse();
});

it('isNullableSetToNull initially returns false', function () {
    expect($this->model->isNullableSetToNull('regnumber'))->toBeFalse();
    expect($this->model->isNullableSetToNull('start'))->toBeFalse();
});

it('listInvalidProperties always returns empty', function () {
    expect($this->model->listInvalidProperties())->toBeEmpty();
});

it('valid always returns true', function () {
    expect($this->model->valid())->toBeTrue();
});
