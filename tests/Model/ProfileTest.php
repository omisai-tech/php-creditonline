<?php

use Omisai\CreditOnline\Model\ActualUsage;
use Omisai\CreditOnline\Model\Profile;

beforeEach(function () {
    $this->model = new Profile;
});

it('getModelName returns Profile', function () {
    expect($this->model->getModelName())->toBe('Profile');
});

it('openAPITypes returns correct type array', function () {
    $types = Profile::openAPITypes();
    expect($types)->toBe([
        'company_name' => 'string',
        'actual_format' => 'string',
        'actual_language' => 'string',
        'actual_usages' => '\Omisai\CreditOnline\Model\ActualUsage',
    ]);
});

it('openAPIFormats returns all null formats', function () {
    $formats = Profile::openAPIFormats();
    expect($formats)->toHaveKeys(['company_name', 'actual_format', 'actual_language', 'actual_usages']);
});

it('attributeMap uses correct original names', function (string $local, string $original) {
    $map = Profile::attributeMap();
    expect($map[$local])->toBe($original);
})->with([
    ['company_name', 'CompanyName'],
    ['actual_format', 'ActualFormat'],
    ['actual_language', 'ActualLanguage'],
    ['actual_usages', 'ActualUsages'],
]);

it('setters returns correct mapping', function (string $property, string $setter) {
    expect(Profile::setters()[$property])->toBe($setter);
})->with([
    ['company_name', 'setCompanyName'],
    ['actual_format', 'setActualFormat'],
    ['actual_language', 'setActualLanguage'],
    ['actual_usages', 'setActualUsages'],
]);

it('getters returns correct mapping', function (string $property, string $getter) {
    expect(Profile::getters()[$property])->toBe($getter);
})->with([
    ['company_name', 'getCompanyName'],
    ['actual_format', 'getActualFormat'],
    ['actual_language', 'getActualLanguage'],
    ['actual_usages', 'getActualUsages'],
]);

it('setCompanyName sets value and returns $this', function () {
    $result = $this->model->setCompanyName('Test Kft.');
    expect($result)->toBe($this->model);
    expect($this->model->getCompanyName())->toBe('Test Kft.');
});

it('setActualFormat sets value and returns $this', function () {
    $result = $this->model->setActualFormat('json');
    expect($result)->toBe($this->model);
    expect($this->model->getActualFormat())->toBe('json');
});

it('setActualLanguage sets value and returns $this', function () {
    $result = $this->model->setActualLanguage('hu');
    expect($result)->toBe($this->model);
    expect($this->model->getActualLanguage())->toBe('hu');
});

it('setActualUsages sets ActualUsage value and returns $this', function () {
    $usage = new ActualUsage;
    $result = $this->model->setActualUsages($usage);
    expect($result)->toBe($this->model);
    expect($this->model->getActualUsages())->toBe($usage);
});

it('setter throws on null for non-nullable properties', function (string $property) {
    $setters = Profile::setters();
    $setter = $setters[$property];
    $this->model->{$setter}(null);
})->throws(InvalidArgumentException::class)->with([
    ['company_name'],
    ['actual_format'],
    ['actual_language'],
    ['actual_usages'],
]);

it('constructor with null sets all properties to null', function () {
    $model = new Profile;
    expect($model->getCompanyName())->toBeNull();
    expect($model->getActualFormat())->toBeNull();
    expect($model->getActualLanguage())->toBeNull();
    expect($model->getActualUsages())->toBeNull();
});

it('constructor with data sets provided properties', function () {
    $usage = new ActualUsage;
    $model = new Profile([
        'company_name' => 'Test Kft.',
        'actual_format' => 'json',
        'actual_language' => 'hu',
        'actual_usages' => $usage,
    ]);
    expect($model->getCompanyName())->toBe('Test Kft.');
    expect($model->getActualFormat())->toBe('json');
    expect($model->getActualLanguage())->toBe('hu');
    expect($model->getActualUsages())->toBe($usage);
});

it('constructor with partial data leaves others null', function () {
    $model = new Profile(['company_name' => 'Test Kft.']);
    expect($model->getCompanyName())->toBe('Test Kft.');
    expect($model->getActualFormat())->toBeNull();
});

it('constructor with empty array initializes all null', function () {
    $model = new Profile([]);
    expect($model->getCompanyName())->toBeNull();
});

it('implements ArrayAccess: offsetExists', function () {
    $this->model->setCompanyName('Test');
    expect($this->model->offsetExists('company_name'))->toBeTrue();
    expect($this->model->offsetExists('nonexistent'))->toBeFalse();
});

it('implements ArrayAccess: offsetGet', function () {
    $this->model->setCompanyName('Test');
    expect($this->model->offsetGet('company_name'))->toBe('Test');
    expect($this->model->offsetGet('nonexistent'))->toBeNull();
});

it('implements ArrayAccess: offsetSet with key', function () {
    $this->model->offsetSet('company_name', 'NewName');
    expect($this->model->offsetGet('company_name'))->toBe('NewName');
});

it('implements ArrayAccess: offsetSet without key', function () {
    $this->model->offsetSet(null, 'value');
    expect($this->model->offsetGet(0))->toBe('value');
});

it('implements ArrayAccess: offsetUnset', function () {
    $this->model->setCompanyName('Test');
    $this->model->offsetUnset('company_name');
    expect($this->model->offsetExists('company_name'))->toBeFalse();
});

it('jsonSerialize returns array', function () {
    $this->model->setCompanyName('Test');
    $serialized = $this->model->jsonSerialize();
    expect($serialized)->toBeObject();
});

it('__toString returns JSON string', function () {
    $this->model->setCompanyName('Test');
    $str = $this->model->__toString();
    expect($str)->toBeString();
    expect($str)->toBeString()->not->toBeEmpty();
});

it('toHeaderValue returns JSON string', function () {
    $this->model->setCompanyName('Test');
    $value = $this->model->toHeaderValue();
    expect($value)->toBeString();
    expect(json_decode($value, true))->toBeArray();
});

it('isNullable returns false for all properties', function (string $property) {
    expect(Profile::isNullable($property))->toBeFalse();
})->with(['company_name', 'actual_format', 'actual_language', 'actual_usages']);

it('isNullable returns false for unknown property', function () {
    expect(Profile::isNullable('unknown'))->toBeFalse();
});

it('isNullableSetToNull initially returns false', function () {
    expect($this->model->isNullableSetToNull('company_name'))->toBeFalse();
    expect($this->model->isNullableSetToNull('actual_usages'))->toBeFalse();
});

it('listInvalidProperties always returns empty', function () {
    expect($this->model->listInvalidProperties())->toBeEmpty();
});

it('valid always returns true', function () {
    expect($this->model->valid())->toBeTrue();
});
