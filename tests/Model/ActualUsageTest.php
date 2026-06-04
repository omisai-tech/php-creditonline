<?php

use Omisai\CreditOnline\Model\ActualUsage;

beforeEach(function () {
    $this->model = new ActualUsage;
});

it('getModelName returns ActualUsage', function () {
    expect($this->model->getModelName())->toBe('ActualUsage');
});

it('openAPITypes returns correct type array', function () {
    $types = ActualUsage::openAPITypes();
    expect($types)->toBe([
        'ids' => 'string[]',
        'limit' => 'int',
        'type' => 'string',
    ]);
});

it('openAPIFormats returns all null formats', function () {
    $formats = ActualUsage::openAPIFormats();
    expect($formats)->toHaveKeys(['ids', 'limit', 'type']);
});

it('attributeMap uses correct original names', function (string $local, string $original) {
    $map = ActualUsage::attributeMap();
    expect($map[$local])->toBe($original);
})->with([
    ['ids', 'Ids'],
    ['limit', 'Limit'],
    ['type', 'Type'],
]);

it('setters returns correct mapping', function (string $property, string $setter) {
    expect(ActualUsage::setters()[$property])->toBe($setter);
})->with([
    ['ids', 'setIds'],
    ['limit', 'setLimit'],
    ['type', 'setType'],
]);

it('getters returns correct mapping', function (string $property, string $getter) {
    expect(ActualUsage::getters()[$property])->toBe($getter);
})->with([
    ['ids', 'getIds'],
    ['limit', 'getLimit'],
    ['type', 'getType'],
]);

it('setIds sets string array value and returns $this', function () {
    $ids = ['id1', 'id2'];
    $result = $this->model->setIds($ids);
    expect($result)->toBe($this->model);
    expect($this->model->getIds())->toBe($ids);
});

it('setIds with empty array and returns $this', function () {
    $result = $this->model->setIds([]);
    expect($result)->toBe($this->model);
    expect($this->model->getIds())->toBe([]);
});

it('setLimit sets int value and returns $this', function () {
    $result = $this->model->setLimit(100);
    expect($result)->toBe($this->model);
    expect($this->model->getLimit())->toBe(100);
});

it('setLimit accepts zero', function () {
    $result = $this->model->setLimit(0);
    expect($result)->toBe($this->model);
    expect($this->model->getLimit())->toBe(0);
});

it('setType sets string value and returns $this', function () {
    $result = $this->model->setType('daily');
    expect($result)->toBe($this->model);
    expect($this->model->getType())->toBe('daily');
});

it('setType with empty string', function () {
    $result = $this->model->setType('');
    expect($result)->toBe($this->model);
    expect($this->model->getType())->toBe('');
});

it('setter throws on null for all properties', function (string $property) {
    $setters = ActualUsage::setters();
    $setter = $setters[$property];
    $this->model->{$setter}(null);
})->throws(InvalidArgumentException::class)->with([
    ['ids'],
    ['limit'],
    ['type'],
]);

it('constructor with null sets all properties to null', function () {
    $model = new ActualUsage;
    expect($model->getIds())->toBeNull();
    expect($model->getLimit())->toBeNull();
    expect($model->getType())->toBeNull();
});

it('constructor with data sets provided properties', function () {
    $model = new ActualUsage([
        'ids' => ['id1', 'id2'],
        'limit' => 100,
        'type' => 'daily',
    ]);
    expect($model->getIds())->toBe(['id1', 'id2']);
    expect($model->getLimit())->toBe(100);
    expect($model->getType())->toBe('daily');
});

it('constructor with empty array values', function () {
    $model = new ActualUsage([
        'ids' => [],
        'limit' => 0,
        'type' => '',
    ]);
    expect($model->getIds())->toBe([]);
    expect($model->getLimit())->toBe(0);
    expect($model->getType())->toBe('');
});

it('constructor with partial data leaves others null', function () {
    $model = new ActualUsage(['limit' => 50]);
    expect($model->getLimit())->toBe(50);
    expect($model->getIds())->toBeNull();
});

it('constructor with empty array initializes all null', function () {
    $model = new ActualUsage([]);
    expect($model->getIds())->toBeNull();
});

it('implements ArrayAccess: offsetExists', function () {
    $this->model->setLimit(100);
    expect($this->model->offsetExists('limit'))->toBeTrue();
    expect($this->model->offsetExists('nonexistent'))->toBeFalse();
});

it('implements ArrayAccess: offsetGet', function () {
    $this->model->setLimit(100);
    expect($this->model->offsetGet('limit'))->toBe(100);
    expect($this->model->offsetGet('nonexistent'))->toBeNull();
});

it('implements ArrayAccess: offsetSet with key', function () {
    $this->model->offsetSet('limit', 50);
    expect($this->model->offsetGet('limit'))->toBe(50);
});

it('implements ArrayAccess: offsetSet without key', function () {
    $this->model->offsetSet(null, 'appended');
    expect($this->model->offsetGet(0))->toBe('appended');
});

it('implements ArrayAccess: offsetUnset', function () {
    $this->model->setLimit(100);
    $this->model->offsetUnset('limit');
    expect($this->model->offsetExists('limit'))->toBeFalse();
});

it('jsonSerialize returns array', function () {
    $this->model->setLimit(100);
    $serialized = $this->model->jsonSerialize();
    expect($serialized)->toBeObject();
});

it('__toString returns JSON string', function () {
    $this->model->setLimit(100);
    $str = $this->model->__toString();
    expect($str)->toBeString();
    expect($str)->toBeString()->not->toBeEmpty();
});

it('toHeaderValue returns JSON string', function () {
    $this->model->setLimit(100);
    $value = $this->model->toHeaderValue();
    expect($value)->toBeString();
    expect(json_decode($value, true))->toBeArray();
});

it('isNullable returns false for all properties', function (string $property) {
    expect(ActualUsage::isNullable($property))->toBeFalse();
})->with(['ids', 'limit', 'type']);

it('isNullable returns false for unknown property', function () {
    expect(ActualUsage::isNullable('unknown'))->toBeFalse();
});

it('isNullableSetToNull initially returns false', function () {
    expect($this->model->isNullableSetToNull('ids'))->toBeFalse();
    expect($this->model->isNullableSetToNull('limit'))->toBeFalse();
});

it('listInvalidProperties always returns empty', function () {
    expect($this->model->listInvalidProperties())->toBeEmpty();
});

it('valid always returns true', function () {
    expect($this->model->valid())->toBeTrue();
});
