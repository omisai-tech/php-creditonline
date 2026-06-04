<?php

use Omisai\CreditOnline\Model\Event;

$eventProperties = [
    'taxnumber',
    'name',
    'category',
    'link',
];

it('returns the model name', function () {
    $model = new Event();
    expect($model->getModelName())->toBe('Event');
});

it('has correct openAPITypes', function () use ($eventProperties) {
    $types = Event::openAPITypes();
    expect($types)->toBeArray()->toHaveCount(count($eventProperties));
    foreach ($eventProperties as $prop) {
        expect($types[$prop])->toBe('string');
    }
});

it('has correct openAPIFormats', function () use ($eventProperties) {
    $formats = Event::openAPIFormats();
    expect($formats)->toBeArray()->toHaveCount(count($eventProperties));
    foreach ($eventProperties as $prop) {
        expect($formats[$prop])->toBeNull();
    }
});

it('has correct attributeMap', function () {
    $map = Event::attributeMap();
    expect($map)->toBeArray();
    expect($map['taxnumber'])->toBe('Taxnumber');
    expect($map['name'])->toBe('Name');
    expect($map['category'])->toBe('Category');
    expect($map['link'])->toBe('Link');
});

it('has correct setters mapping', function () {
    $setters = Event::setters();
    expect($setters)->toBeArray();
    expect($setters['taxnumber'])->toBe('setTaxnumber');
    expect($setters['name'])->toBe('setName');
    expect($setters['category'])->toBe('setCategory');
    expect($setters['link'])->toBe('setLink');
});

it('has correct getters mapping', function () {
    $getters = Event::getters();
    expect($getters)->toBeArray();
    expect($getters['taxnumber'])->toBe('getTaxnumber');
    expect($getters['name'])->toBe('getName');
    expect($getters['category'])->toBe('getCategory');
    expect($getters['link'])->toBe('getLink');
});

it('defaults all properties to null on construction with no data', function () use ($eventProperties) {
    $model = new Event();
    foreach ($eventProperties as $prop) {
        $method = 'get' . str_replace('_', '', ucwords($prop, '_'));
        expect($model->$method())->toBeNull();
    }
});

it('defaults all properties to null on construction with null', function () use ($eventProperties) {
    $model = new Event(null);
    foreach ($eventProperties as $prop) {
        $method = 'get' . str_replace('_', '', ucwords($prop, '_'));
        expect($model->$method())->toBeNull();
    }
});

it('defaults all properties to null on construction with empty array', function () use ($eventProperties) {
    $model = new Event([]);
    foreach ($eventProperties as $prop) {
        $method = 'get' . str_replace('_', '', ucwords($prop, '_'));
        expect($model->$method())->toBeNull();
    }
});

it('sets properties from construction data array', function () {
    $model = new Event([
        'taxnumber' => '12345678-2-41',
        'name' => 'Test Event Company',
        'category' => 'General',
        'link' => 'https://example.com/event',
    ]);
    expect($model->getTaxnumber())->toBe('12345678-2-41');
    expect($model->getName())->toBe('Test Event Company');
    expect($model->getCategory())->toBe('General');
    expect($model->getLink())->toBe('https://example.com/event');
});

it('sets partial properties from construction data', function () {
    $model = new Event([
        'taxnumber' => '12345678-2-41',
        'name' => 'Partial Event',
    ]);
    expect($model->getTaxnumber())->toBe('12345678-2-41');
    expect($model->getName())->toBe('Partial Event');
    expect($model->getCategory())->toBeNull();
    expect($model->getLink())->toBeNull();
});

it('getters and setters work correctly', function (string $property) {
    $model = new Event();
    $setter = 'set' . str_replace('_', '', ucwords($property, '_'));
    $getter = 'get' . str_replace('_', '', ucwords($property, '_'));
    $result = $model->$setter('test_value');
    expect($result)->toBe($model);
    expect($model->$getter())->toBe('test_value');
})->with($eventProperties);

it('setters can set empty string', function (string $property) {
    $model = new Event();
    $setter = 'set' . str_replace('_', '', ucwords($property, '_'));
    $getter = 'get' . str_replace('_', '', ucwords($property, '_'));
    $model->$setter('');
    expect($model->$getter())->toBe('');
})->with($eventProperties);

it('non-nullable setters throw on null', function (string $property) {
    $model = new Event();
    $setter = 'set' . str_replace('_', '', ucwords($property, '_'));
    $model->$setter(null);
})->with($eventProperties)->throws(\InvalidArgumentException::class);

it('listInvalidProperties returns empty array (no required fields)', function () {
    $model = new Event();
    expect($model->listInvalidProperties())->toBe([]);
});

it('valid returns true for default state', function () {
    $model = new Event();
    expect($model->valid())->toBeTrue();
});

it('valid returns true after setting properties', function () {
    $model = new Event(['name' => 'Some Event']);
    expect($model->valid())->toBeTrue();
});

it('implements ArrayAccess: offsetExists', function () {
    $model = new Event(['name' => 'Test Event']);
    expect($model->offsetExists('name'))->toBeTrue();
    expect($model->offsetExists('nonexistent'))->toBeFalse();
});

it('implements ArrayAccess: offsetGet', function () {
    $model = new Event(['name' => 'Test Event']);
    expect($model->offsetGet('name'))->toBe('Test Event');
    expect($model->offsetGet('nonexistent'))->toBeNull();
});

it('implements ArrayAccess: offsetSet with key', function () {
    $model = new Event();
    $model->offsetSet('name', 'New Event Name');
    expect($model->offsetGet('name'))->toBe('New Event Name');
});

it('implements ArrayAccess: offsetSet without key (append)', function () {
    $model = new Event();
    $model->offsetSet(null, 'unkeyed');
    expect($model->offsetGet(0))->toBe('unkeyed');
});

it('implements ArrayAccess: offsetUnset', function () {
    $model = new Event(['name' => 'Test Event']);
    $model->offsetUnset('name');
    expect($model->offsetExists('name'))->toBeFalse();
});

it('jsonSerialize returns object with PascalCase keys', function () {
    $model = new Event(['name' => 'Test Event', 'category' => 'News']);
    $result = $model->jsonSerialize();
    expect($result)->toBeObject();
    expect($result->Name)->toBe('Test Event');
    expect($result->Category)->toBe('News');
});

it('jsonSerialize omits null properties', function () {
    $model = new Event(['name' => 'Test Event']);
    $result = $model->jsonSerialize();
    expect(property_exists($result, 'Name'))->toBeTrue();
    expect(property_exists($result, 'Category'))->toBeFalse();
});

it('__toString returns JSON string with PascalCase keys', function () {
    $model = new Event(['name' => 'Test Event']);
    $str = (string) $model;
    expect($str)->toBeString();
    $decoded = json_decode($str, true);
    expect($decoded['Name'])->toBe('Test Event');
});

it('toHeaderValue returns compact JSON', function () {
    $model = new Event(['name' => 'Test Event']);
    $value = $model->toHeaderValue();
    expect($value)->toBeString();
    expect(str_contains($value, "\n"))->toBeFalse();
});

it('isNullable returns false for all properties', function (string $property) {
    expect(Event::isNullable($property))->toBeFalse();
})->with($eventProperties);

it('isNullable returns false for unknown property', function () {
    expect(Event::isNullable('unknown'))->toBeFalse();
});

it('isNullableSetToNull returns false for set properties', function (string $property) {
    $model = new Event([$property => 'some_value']);
    expect($model->isNullableSetToNull($property))->toBeFalse();
})->with($eventProperties);
