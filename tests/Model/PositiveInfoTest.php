<?php

use Omisai\CreditOnline\Model\PositiveInfo;

beforeEach(function () {
    $this->model = new PositiveInfo;
});

it('getModelName returns PositiveInfo', function () {
    expect($this->model->getModelName())->toBe('PositiveInfo');
});

it('openAPITypes returns correct type array', function () {
    $types = PositiveInfo::openAPITypes();
    expect($types)->toBe([
        'type' => 'string',
        'start' => '\DateTime',
        'end' => '\DateTime',
    ]);
});

it('openAPIFormats has date format for start and end', function () {
    $formats = PositiveInfo::openAPIFormats();
    expect($formats)->toHaveKeys(['type', 'start', 'end']);
    expect($formats['start'])->toBe('date');
    expect($formats['end'])->toBe('date');
});

it('attributeMap uses correct original names', function (string $local, string $original) {
    $map = PositiveInfo::attributeMap();
    expect($map[$local])->toBe($original);
})->with([
    ['type', 'Type'],
    ['start', 'Start'],
    ['end', 'End'],
]);

it('setters returns correct mapping', function (string $property, string $setter) {
    expect(PositiveInfo::setters()[$property])->toBe($setter);
})->with([
    ['type', 'setType'],
    ['start', 'setStart'],
    ['end', 'setEnd'],
]);

it('getters returns correct mapping', function (string $property, string $getter) {
    expect(PositiveInfo::getters()[$property])->toBe($getter);
})->with([
    ['type', 'getType'],
    ['start', 'getStart'],
    ['end', 'getEnd'],
]);

it('setType sets value and returns $this', function () {
    $result = $this->model->setType('Pozitív információ');
    expect($result)->toBe($this->model);
    expect($this->model->getType())->toBe('Pozitív információ');
});

it('setStart sets DateTime value and returns $this', function () {
    $date = new DateTime('2023-01-15');
    $result = $this->model->setStart($date);
    expect($result)->toBe($this->model);
    expect($this->model->getStart())->toBe($date);
});

it('setEnd sets DateTime value and returns $this', function () {
    $date = new DateTime('2024-12-31');
    $result = $this->model->setEnd($date);
    expect($result)->toBe($this->model);
    expect($this->model->getEnd())->toBe($date);
});

it('setter throws on null for non-nullable properties', function (string $property) {
    $setters = PositiveInfo::setters();
    $setter = $setters[$property];
    $this->model->{$setter}(null);
})->throws(InvalidArgumentException::class)->with([
    ['type'],
    ['start'],
    ['end'],
]);

it('constructor with null sets all properties to null', function () {
    $model = new PositiveInfo;
    expect($model->getType())->toBeNull();
    expect($model->getStart())->toBeNull();
    expect($model->getEnd())->toBeNull();
});

it('constructor with data sets provided properties', function () {
    $start = new DateTime('2023-01-15');
    $end = new DateTime('2024-12-31');
    $model = new PositiveInfo([
        'type' => 'Pozitív információ',
        'start' => $start,
        'end' => $end,
    ]);
    expect($model->getType())->toBe('Pozitív információ');
    expect($model->getStart())->toBe($start);
    expect($model->getEnd())->toBe($end);
});

it('constructor with partial data leaves others null', function () {
    $model = new PositiveInfo(['type' => 'Pozitív információ']);
    expect($model->getType())->toBe('Pozitív információ');
    expect($model->getStart())->toBeNull();
});

it('constructor with empty array initializes all null', function () {
    $model = new PositiveInfo([]);
    expect($model->getType())->toBeNull();
});

it('implements ArrayAccess: offsetExists', function () {
    $this->model->setType('Pozitív információ');
    expect($this->model->offsetExists('type'))->toBeTrue();
    expect($this->model->offsetExists('nonexistent'))->toBeFalse();
});

it('implements ArrayAccess: offsetGet', function () {
    $this->model->setType('Pozitív információ');
    expect($this->model->offsetGet('type'))->toBe('Pozitív információ');
    expect($this->model->offsetGet('nonexistent'))->toBeNull();
});

it('implements ArrayAccess: offsetSet with key', function () {
    $this->model->offsetSet('type', 'Más információ');
    expect($this->model->offsetGet('type'))->toBe('Más információ');
});

it('implements ArrayAccess: offsetSet without key', function () {
    $this->model->offsetSet(null, 'value');
    expect($this->model->offsetGet(0))->toBe('value');
});

it('implements ArrayAccess: offsetUnset', function () {
    $this->model->setType('Pozitív információ');
    $this->model->offsetUnset('type');
    expect($this->model->offsetExists('type'))->toBeFalse();
});

it('jsonSerialize returns array', function () {
    $this->model->setType('Pozitív információ');
    $serialized = $this->model->jsonSerialize();
    expect($serialized)->toBeObject();
});

it('__toString returns JSON string', function () {
    $this->model->setType('Pozitív információ');
    $str = $this->model->__toString();
    expect($str)->toBeString();
    expect($str)->toBeString()->not->toBeEmpty();
});

it('toHeaderValue returns JSON string', function () {
    $this->model->setType('Pozitív informação');
    $value = $this->model->toHeaderValue();
    expect($value)->toBeString();
    expect(json_decode($value, true))->toBeArray();
});

it('isNullable returns false for all properties', function (string $property) {
    expect(PositiveInfo::isNullable($property))->toBeFalse();
})->with(['type', 'start', 'end']);

it('isNullable returns false for unknown property', function () {
    expect(PositiveInfo::isNullable('unknown'))->toBeFalse();
});

it('isNullableSetToNull initially returns false', function () {
    expect($this->model->isNullableSetToNull('type'))->toBeFalse();
    expect($this->model->isNullableSetToNull('start'))->toBeFalse();
});

it('listInvalidProperties always returns empty', function () {
    expect($this->model->listInvalidProperties())->toBeEmpty();
});

it('valid always returns true', function () {
    expect($this->model->valid())->toBeTrue();
});
