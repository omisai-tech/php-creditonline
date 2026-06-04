<?php

use Omisai\CreditOnline\Model\Event;

beforeEach(function () {
    $this->model = new Event;
});

it('getModelName returns Event', function () {
    expect($this->model->getModelName())->toBe('Event');
});

it('openAPITypes returns correct type array', function () {
    $types = Event::openAPITypes();
    expect($types)->toBe([
        'taxnumber' => 'string',
        'name' => 'string',
        'category' => 'string',
        'link' => 'string',
    ]);
});

it('openAPIFormats returns all null formats', function () {
    $formats = Event::openAPIFormats();
    expect($formats)->toHaveKeys(['taxnumber', 'name', 'category', 'link']);
});

it('attributeMap uses correct original names', function (string $local, string $original) {
    $map = Event::attributeMap();
    expect($map[$local])->toBe($original);
})->with([
    ['taxnumber', 'Taxnumber'],
    ['name', 'Name'],
    ['category', 'Category'],
    ['link', 'Link'],
]);

it('setters returns correct mapping', function (string $property, string $setter) {
    expect(Event::setters()[$property])->toBe($setter);
})->with([
    ['taxnumber', 'setTaxnumber'],
    ['name', 'setName'],
    ['category', 'setCategory'],
    ['link', 'setLink'],
]);

it('getters returns correct mapping', function (string $property, string $getter) {
    expect(Event::getters()[$property])->toBe($getter);
})->with([
    ['taxnumber', 'getTaxnumber'],
    ['name', 'getName'],
    ['category', 'getCategory'],
    ['link', 'getLink'],
]);

it('setTaxnumber sets value and returns $this', function () {
    $result = $this->model->setTaxnumber('12345678-2-42');
    expect($result)->toBe($this->model);
    expect($this->model->getTaxnumber())->toBe('12345678-2-42');
});

it('setName sets value and returns $this', function () {
    $result = $this->model->setName('Test Event');
    expect($result)->toBe($this->model);
    expect($this->model->getName())->toBe('Test Event');
});

it('setCategory sets value and returns $this', function () {
    $result = $this->model->setCategory('change');
    expect($result)->toBe($this->model);
    expect($this->model->getCategory())->toBe('change');
});

it('setLink sets value and returns $this', function () {
    $result = $this->model->setLink('https://example.com');
    expect($result)->toBe($this->model);
    expect($this->model->getLink())->toBe('https://example.com');
});

it('setter throws on null for non-nullable properties', function (string $property) {
    $setters = Event::setters();
    $setter = $setters[$property];
    $this->model->{$setter}(null);
})->throws(InvalidArgumentException::class)->with([
    ['taxnumber'],
    ['name'],
    ['category'],
    ['link'],
]);

it('constructor with null sets all properties to null', function () {
    $model = new Event;
    expect($model->getTaxnumber())->toBeNull();
    expect($model->getName())->toBeNull();
    expect($model->getCategory())->toBeNull();
    expect($model->getLink())->toBeNull();
});

it('constructor with data sets provided properties', function () {
    $model = new Event([
        'taxnumber' => '12345678-2-42',
        'name' => 'Test Company',
        'category' => 'new',
        'link' => 'https://example.com',
    ]);
    expect($model->getTaxnumber())->toBe('12345678-2-42');
    expect($model->getName())->toBe('Test Company');
    expect($model->getCategory())->toBe('new');
    expect($model->getLink())->toBe('https://example.com');
});

it('constructor with partial data leaves others null', function () {
    $model = new Event(['name' => 'Test']);
    expect($model->getName())->toBe('Test');
    expect($model->getTaxnumber())->toBeNull();
});

it('constructor with empty array initializes all null', function () {
    $model = new Event([]);
    expect($model->getTaxnumber())->toBeNull();
});

it('implements ArrayAccess: offsetExists', function () {
    $this->model->setName('Test');
    expect($this->model->offsetExists('name'))->toBeTrue();
    expect($this->model->offsetExists('nonexistent'))->toBeFalse();
});

it('implements ArrayAccess: offsetGet', function () {
    $this->model->setName('Test');
    expect($this->model->offsetGet('name'))->toBe('Test');
    expect($this->model->offsetGet('nonexistent'))->toBeNull();
});

it('implements ArrayAccess: offsetSet with key', function () {
    $this->model->offsetSet('name', 'NewName');
    expect($this->model->offsetGet('name'))->toBe('NewName');
});

it('implements ArrayAccess: offsetSet without key', function () {
    $this->model->offsetSet(null, 'value');
    expect($this->model->offsetGet(0))->toBe('value');
});

it('implements ArrayAccess: offsetUnset', function () {
    $this->model->setName('Test');
    $this->model->offsetUnset('name');
    expect($this->model->offsetExists('name'))->toBeFalse();
});

it('jsonSerialize returns array', function () {
    $this->model->setName('Test');
    $serialized = $this->model->jsonSerialize();
    expect($serialized)->toBeObject();
});

it('__toString returns JSON string', function () {
    $this->model->setName('Test');
    $str = $this->model->__toString();
    expect($str)->toBeString();
    expect($str)->toBeString()->not->toBeEmpty();
});

it('toHeaderValue returns JSON string', function () {
    $this->model->setName('Test');
    $value = $this->model->toHeaderValue();
    expect($value)->toBeString();
    expect(json_decode($value, true))->toBeArray();
});

it('isNullable returns false for all properties', function (string $property) {
    expect(Event::isNullable($property))->toBeFalse();
})->with(['taxnumber', 'name', 'category', 'link']);

it('isNullable returns false for unknown property', function () {
    expect(Event::isNullable('unknown'))->toBeFalse();
});

it('isNullableSetToNull initially returns false', function () {
    expect($this->model->isNullableSetToNull('taxnumber'))->toBeFalse();
    expect($this->model->isNullableSetToNull('name'))->toBeFalse();
});

it('listInvalidProperties always returns empty', function () {
    expect($this->model->listInvalidProperties())->toBeEmpty();
});

it('valid always returns true', function () {
    expect($this->model->valid())->toBeTrue();
});
