<?php

use Omisai\CreditOnline\Model\ActualUsage;

$actualUsageProperties = [
    'ids',
    'limit',
    'type',
];

it('returns the model name', function () {
    $model = new ActualUsage();
    expect($model->getModelName())->toBe('ActualUsage');
});

it('has correct openAPITypes', function () use ($actualUsageProperties) {
    $types = ActualUsage::openAPITypes();
    expect($types)->toBeArray()->toHaveCount(count($actualUsageProperties));
    expect($types['ids'])->toBe('string[]');
    expect($types['limit'])->toBe('int');
    expect($types['type'])->toBe('string');
});

it('has correct openAPIFormats', function () use ($actualUsageProperties) {
    $formats = ActualUsage::openAPIFormats();
    expect($formats)->toBeArray()->toHaveCount(count($actualUsageProperties));
    foreach ($actualUsageProperties as $prop) {
        expect($formats[$prop])->toBeNull();
    }
});

it('has correct attributeMap', function () {
    $map = ActualUsage::attributeMap();
    expect($map)->toBeArray();
    expect($map['ids'])->toBe('Ids');
    expect($map['limit'])->toBe('Limit');
    expect($map['type'])->toBe('Type');
});

it('has correct setters mapping', function () {
    $setters = ActualUsage::setters();
    expect($setters)->toBeArray();
    expect($setters['ids'])->toBe('setIds');
    expect($setters['limit'])->toBe('setLimit');
    expect($setters['type'])->toBe('setType');
});

it('has correct getters mapping', function () {
    $getters = ActualUsage::getters();
    expect($getters)->toBeArray();
    expect($getters['ids'])->toBe('getIds');
    expect($getters['limit'])->toBe('getLimit');
    expect($getters['type'])->toBe('getType');
});

it('defaults all properties to null on construction with no data', function () use ($actualUsageProperties) {
    $model = new ActualUsage();
    foreach ($actualUsageProperties as $prop) {
        $method = 'get' . str_replace('_', '', ucwords($prop, '_'));
        expect($model->$method())->toBeNull();
    }
});

it('defaults all properties to null on construction with null', function () use ($actualUsageProperties) {
    $model = new ActualUsage(null);
    foreach ($actualUsageProperties as $prop) {
        $method = 'get' . str_replace('_', '', ucwords($prop, '_'));
        expect($model->$method())->toBeNull();
    }
});

it('defaults all properties to null on construction with empty array', function () use ($actualUsageProperties) {
    $model = new ActualUsage([]);
    foreach ($actualUsageProperties as $prop) {
        $method = 'get' . str_replace('_', '', ucwords($prop, '_'));
        expect($model->$method())->toBeNull();
    }
});

it('sets properties from construction data array', function () {
    $model = new ActualUsage([
        'ids' => ['id1', 'id2'],
        'limit' => 100,
        'type' => 'company',
    ]);
    expect($model->getIds())->toBe(['id1', 'id2']);
    expect($model->getLimit())->toBe(100);
    expect($model->getType())->toBe('company');
});

it('sets partial properties from construction data', function () {
    $model = new ActualUsage([
        'limit' => 50,
        'type' => 'basic',
    ]);
    expect($model->getIds())->toBeNull();
    expect($model->getLimit())->toBe(50);
    expect($model->getType())->toBe('basic');
});

it('getter and setter for ids (string array) works correctly', function () {
    $model = new ActualUsage();
    $result = $model->setIds(['abc', 'def']);
    expect($result)->toBe($model);
    expect($model->getIds())->toBe(['abc', 'def']);
});

it('getter and setter for limit (int) works correctly', function () {
    $model = new ActualUsage();
    $result = $model->setLimit(500);
    expect($result)->toBe($model);
    expect($model->getLimit())->toBe(500);
});

it('getter and setter for type (string) works correctly', function () {
    $model = new ActualUsage();
    $result = $model->setType('premium');
    expect($result)->toBe($model);
    expect($model->getType())->toBe('premium');
});

it('setters return self for fluid interface', function (string $property) {
    $model = new ActualUsage();
    $setter = 'set' . str_replace('_', '', ucwords($property, '_'));
    $values = ['ids' => [], 'limit' => 0, 'type' => ''];
    $result = $model->$setter($values[$property]);
    expect($result)->toBe($model);
})->with($actualUsageProperties);

it('non-nullable setters throw on null', function (string $property) {
    $model = new ActualUsage();
    $setter = 'set' . str_replace('_', '', ucwords($property, '_'));
    $model->$setter(null);
})->with($actualUsageProperties)->throws(\InvalidArgumentException::class);

it('setIds accepts empty array', function () {
    $model = new ActualUsage();
    $model->setIds([]);
    expect($model->getIds())->toBe([]);
});

it('setLimit accepts zero', function () {
    $model = new ActualUsage();
    $model->setLimit(0);
    expect($model->getLimit())->toBe(0);
});

it('setType accepts empty string', function () {
    $model = new ActualUsage();
    $model->setType('');
    expect($model->getType())->toBe('');
});

it('listInvalidProperties returns empty array (no required fields)', function () {
    $model = new ActualUsage();
    expect($model->listInvalidProperties())->toBe([]);
});

it('valid returns true for default state', function () {
    $model = new ActualUsage();
    expect($model->valid())->toBeTrue();
});

it('valid returns true after setting properties', function () {
    $model = new ActualUsage(['limit' => 100]);
    expect($model->valid())->toBeTrue();
});

it('implements ArrayAccess: offsetExists', function () {
    $model = new ActualUsage(['limit' => 100]);
    expect($model->offsetExists('limit'))->toBeTrue();
    expect($model->offsetExists('nonexistent'))->toBeFalse();
});

it('implements ArrayAccess: offsetGet', function () {
    $model = new ActualUsage(['limit' => 100]);
    expect($model->offsetGet('limit'))->toBe(100);
    expect($model->offsetGet('nonexistent'))->toBeNull();
});

it('implements ArrayAccess: offsetSet with key', function () {
    $model = new ActualUsage();
    $model->offsetSet('limit', 200);
    expect($model->offsetGet('limit'))->toBe(200);
});

it('implements ArrayAccess: offsetSet without key (append)', function () {
    $model = new ActualUsage();
    $model->offsetSet(null, 'appended');
    expect($model->offsetGet(0))->toBe('appended');
});

it('implements ArrayAccess: offsetUnset', function () {
    $model = new ActualUsage(['limit' => 100]);
    $model->offsetUnset('limit');
    expect($model->offsetExists('limit'))->toBeFalse();
});

it('jsonSerialize returns object with PascalCase keys', function () {
    $model = new ActualUsage(['ids' => ['a', 'b'], 'limit' => 50]);
    $result = $model->jsonSerialize();
    expect($result)->toBeObject();
    expect($result->Ids)->toBe(['a', 'b']);
    expect($result->Limit)->toBe(50);
});

it('jsonSerialize omits null properties', function () {
    $model = new ActualUsage(['type' => 'basic']);
    $result = $model->jsonSerialize();
    expect(property_exists($result, 'Type'))->toBeTrue();
    expect(property_exists($result, 'Ids'))->toBeFalse();
    expect(property_exists($result, 'Limit'))->toBeFalse();
});

it('__toString returns JSON string with PascalCase keys', function () {
    $model = new ActualUsage(['type' => 'basic']);
    $str = (string) $model;
    expect($str)->toBeString();
    $decoded = json_decode($str, true);
    expect($decoded['Type'])->toBe('basic');
});

it('toHeaderValue returns compact JSON', function () {
    $model = new ActualUsage(['type' => 'basic']);
    $value = $model->toHeaderValue();
    expect($value)->toBeString();
    expect(str_contains($value, "\n"))->toBeFalse();
});

it('isNullable returns false for all properties', function (string $property) {
    expect(ActualUsage::isNullable($property))->toBeFalse();
})->with($actualUsageProperties);

it('isNullable returns false for unknown property', function () {
    expect(ActualUsage::isNullable('unknown'))->toBeFalse();
});

it('isNullableSetToNull returns false for set properties', function (string $property) {
    $values = ['ids' => [], 'limit' => 0, 'type' => 'test'];
    $model = new ActualUsage([$property => $values[$property]]);
    expect($model->isNullableSetToNull($property))->toBeFalse();
})->with($actualUsageProperties);
