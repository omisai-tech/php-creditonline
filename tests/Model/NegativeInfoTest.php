<?php

use Omisai\CreditOnline\Model\NegativeInfo;

beforeEach(function () {
    $this->model = new NegativeInfo;
});

it('getModelName returns NegativeInfo', function () {
    expect($this->model->getModelName())->toBe('NegativeInfo');
});

it('openAPITypes returns correct type array', function () {
    $types = NegativeInfo::openAPITypes();
    expect($types)->toBe([
        'type' => 'string',
        'case_number' => 'string',
        'start' => '\DateTime',
        'end' => '\DateTime',
    ]);
});

it('openAPIFormats has date format for start and end', function () {
    $formats = NegativeInfo::openAPIFormats();
    expect($formats)->toHaveKeys(['type', 'case_number', 'start', 'end']);
    expect($formats['start'])->toBe('date');
    expect($formats['end'])->toBe('date');
});

it('attributeMap uses correct original names', function (string $local, string $original) {
    $map = NegativeInfo::attributeMap();
    expect($map[$local])->toBe($original);
})->with([
    ['type', 'Type'],
    ['case_number', 'CaseNumber'],
    ['start', 'Start'],
    ['end', 'End'],
]);

it('setters returns correct mapping', function (string $property, string $setter) {
    expect(NegativeInfo::setters()[$property])->toBe($setter);
})->with([
    ['type', 'setType'],
    ['case_number', 'setCaseNumber'],
    ['start', 'setStart'],
    ['end', 'setEnd'],
]);

it('getters returns correct mapping', function (string $property, string $getter) {
    expect(NegativeInfo::getters()[$property])->toBe($getter);
})->with([
    ['type', 'getType'],
    ['case_number', 'getCaseNumber'],
    ['start', 'getStart'],
    ['end', 'getEnd'],
]);

it('setType sets value and returns $this', function () {
    $result = $this->model->setType('Végrehajtás');
    expect($result)->toBe($this->model);
    expect($this->model->getType())->toBe('Végrehajtás');
});

it('setCaseNumber sets value and returns $this', function () {
    $result = $this->model->setCaseNumber('ABC-123');
    expect($result)->toBe($this->model);
    expect($this->model->getCaseNumber())->toBe('ABC-123');
});

it('setStart sets DateTime value and returns $this', function () {
    $date = new DateTime('2023-01-15');
    $result = $this->model->setStart($date);
    expect($result)->toBe($this->model);
    expect($this->model->getStart())->toBe($date);
});

it('setEnd sets DateTime value and returns $this', function () {
    $date = new DateTime('2024-06-30');
    $result = $this->model->setEnd($date);
    expect($result)->toBe($this->model);
    expect($this->model->getEnd())->toBe($date);
});

it('setter throws on null for non-nullable properties', function (string $property) {
    $setters = NegativeInfo::setters();
    $setter = $setters[$property];
    $this->model->{$setter}(null);
})->throws(InvalidArgumentException::class)->with([
    ['type'],
    ['case_number'],
    ['start'],
    ['end'],
]);

it('constructor with null sets all properties to null', function () {
    $model = new NegativeInfo;
    expect($model->getType())->toBeNull();
    expect($model->getCaseNumber())->toBeNull();
    expect($model->getStart())->toBeNull();
    expect($model->getEnd())->toBeNull();
});

it('constructor with data sets provided properties', function () {
    $start = new DateTime('2023-01-15');
    $end = new DateTime('2024-06-30');
    $model = new NegativeInfo([
        'type' => 'Végrehajtás',
        'case_number' => 'ABC-123',
        'start' => $start,
        'end' => $end,
    ]);
    expect($model->getType())->toBe('Végrehajtás');
    expect($model->getCaseNumber())->toBe('ABC-123');
    expect($model->getStart())->toBe($start);
    expect($model->getEnd())->toBe($end);
});

it('constructor with partial data leaves others null', function () {
    $model = new NegativeInfo(['type' => 'Végrehajtás']);
    expect($model->getType())->toBe('Végrehajtás');
    expect($model->getCaseNumber())->toBeNull();
});

it('constructor with empty array initializes all null', function () {
    $model = new NegativeInfo([]);
    expect($model->getType())->toBeNull();
});

it('implements ArrayAccess: offsetExists', function () {
    $this->model->setType('Végrehajtás');
    expect($this->model->offsetExists('type'))->toBeTrue();
    expect($this->model->offsetExists('nonexistent'))->toBeFalse();
});

it('implements ArrayAccess: offsetGet', function () {
    $this->model->setType('Végrehajtás');
    expect($this->model->offsetGet('type'))->toBe('Végrehajtás');
    expect($this->model->offsetGet('nonexistent'))->toBeNull();
});

it('implements ArrayAccess: offsetSet with key', function () {
    $this->model->offsetSet('type', 'Felszámolás');
    expect($this->model->offsetGet('type'))->toBe('Felszámolás');
});

it('implements ArrayAccess: offsetSet without key', function () {
    $this->model->offsetSet(null, 'value');
    expect($this->model->offsetGet(0))->toBe('value');
});

it('implements ArrayAccess: offsetUnset', function () {
    $this->model->setType('Végrehajtás');
    $this->model->offsetUnset('type');
    expect($this->model->offsetExists('type'))->toBeFalse();
});

it('jsonSerialize returns array', function () {
    $this->model->setType('Végrehajtás');
    $serialized = $this->model->jsonSerialize();
    expect($serialized)->toBeObject();
});

it('__toString returns JSON string', function () {
    $this->model->setType('Végrehajtás');
    $str = $this->model->__toString();
    expect($str)->toBeString();
    expect($str)->toBeString()->not->toBeEmpty();
});

it('toHeaderValue returns JSON string', function () {
    $this->model->setType('Végrehajtás');
    $value = $this->model->toHeaderValue();
    expect($value)->toBeString();
    expect(json_decode($value, true))->toBeArray();
});

it('isNullable returns false for all properties', function (string $property) {
    expect(NegativeInfo::isNullable($property))->toBeFalse();
})->with(['type', 'case_number', 'start', 'end']);

it('isNullable returns false for unknown property', function () {
    expect(NegativeInfo::isNullable('unknown'))->toBeFalse();
});

it('isNullableSetToNull initially returns false', function () {
    expect($this->model->isNullableSetToNull('type'))->toBeFalse();
    expect($this->model->isNullableSetToNull('case_number'))->toBeFalse();
});

it('listInvalidProperties always returns empty', function () {
    expect($this->model->listInvalidProperties())->toBeEmpty();
});

it('valid always returns true', function () {
    expect($this->model->valid())->toBeTrue();
});
