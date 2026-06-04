<?php

use Omisai\CreditOnline\Model\ActualUsage;
use Omisai\CreditOnline\Model\Profile;

$profileProperties = [
    'company_name',
    'actual_format',
    'actual_language',
    'actual_usages',
];

it('returns the model name', function () {
    $model = new Profile();
    expect($model->getModelName())->toBe('Profile');
});

it('has correct openAPITypes', function () use ($profileProperties) {
    $types = Profile::openAPITypes();
    expect($types)->toBeArray()->toHaveCount(count($profileProperties));
    expect($types['company_name'])->toBe('string');
    expect($types['actual_format'])->toBe('string');
    expect($types['actual_language'])->toBe('string');
    expect($types['actual_usages'])->toBe('\Omisai\CreditOnline\Model\ActualUsage');
});

it('has correct openAPIFormats', function () use ($profileProperties) {
    $formats = Profile::openAPIFormats();
    expect($formats)->toBeArray()->toHaveCount(count($profileProperties));
    foreach ($profileProperties as $prop) {
        expect($formats[$prop])->toBeNull();
    }
});

it('has correct attributeMap', function () {
    $map = Profile::attributeMap();
    expect($map)->toBeArray();
    expect($map['company_name'])->toBe('CompanyName');
    expect($map['actual_format'])->toBe('ActualFormat');
    expect($map['actual_language'])->toBe('ActualLanguage');
    expect($map['actual_usages'])->toBe('ActualUsages');
});

it('has correct setters mapping', function () {
    $setters = Profile::setters();
    expect($setters)->toBeArray();
    expect($setters['company_name'])->toBe('setCompanyName');
    expect($setters['actual_format'])->toBe('setActualFormat');
    expect($setters['actual_language'])->toBe('setActualLanguage');
    expect($setters['actual_usages'])->toBe('setActualUsages');
});

it('has correct getters mapping', function () {
    $getters = Profile::getters();
    expect($getters)->toBeArray();
    expect($getters['company_name'])->toBe('getCompanyName');
    expect($getters['actual_format'])->toBe('getActualFormat');
    expect($getters['actual_language'])->toBe('getActualLanguage');
    expect($getters['actual_usages'])->toBe('getActualUsages');
});

it('defaults all properties to null on construction with no data', function () use ($profileProperties) {
    $model = new Profile();
    foreach ($profileProperties as $prop) {
        $method = 'get' . str_replace('_', '', ucwords($prop, '_'));
        expect($model->$method())->toBeNull();
    }
});

it('defaults all properties to null on construction with null', function () use ($profileProperties) {
    $model = new Profile(null);
    foreach ($profileProperties as $prop) {
        $method = 'get' . str_replace('_', '', ucwords($prop, '_'));
        expect($model->$method())->toBeNull();
    }
});

it('defaults all properties to null on construction with empty array', function () use ($profileProperties) {
    $model = new Profile([]);
    foreach ($profileProperties as $prop) {
        $method = 'get' . str_replace('_', '', ucwords($prop, '_'));
        expect($model->$method())->toBeNull();
    }
});

it('sets properties from construction data array', function () {
    $usage = new ActualUsage();
    $model = new Profile([
        'company_name' => 'Test Company',
        'actual_format' => 'json',
        'actual_language' => 'hu',
        'actual_usages' => $usage,
    ]);
    expect($model->getCompanyName())->toBe('Test Company');
    expect($model->getActualFormat())->toBe('json');
    expect($model->getActualLanguage())->toBe('hu');
    expect($model->getActualUsages())->toBe($usage);
});

it('sets partial properties from construction data', function () {
    $model = new Profile([
        'company_name' => 'Partial Company',
        'actual_language' => 'en',
    ]);
    expect($model->getCompanyName())->toBe('Partial Company');
    expect($model->getActualLanguage())->toBe('en');
    expect($model->getActualFormat())->toBeNull();
    expect($model->getActualUsages())->toBeNull();
});

it('getters and setters work correctly for string properties', function (string $property) {
    $model = new Profile();
    $setter = 'set' . str_replace('_', '', ucwords($property, '_'));
    $getter = 'get' . str_replace('_', '', ucwords($property, '_'));
    $result = $model->$setter('test_value');
    expect($result)->toBe($model);
    expect($model->$getter())->toBe('test_value');
})->with(['company_name', 'actual_format', 'actual_language']);

it('setActualUsages accepts ActualUsage object', function () {
    $model = new Profile();
    $usage = new ActualUsage();
    $result = $model->setActualUsages($usage);
    expect($result)->toBe($model);
    expect($model->getActualUsages())->toBe($usage);
});

it('setters return self for fluid interface', function (string $property) {
    $model = new Profile();
    $setter = 'set' . str_replace('_', '', ucwords($property, '_'));
    $value = $property === 'actual_usages' ? new ActualUsage() : 'test';
    $result = $model->$setter($value);
    expect($result)->toBe($model);
})->with($profileProperties);

it('non-nullable setters throw on null', function (string $property) {
    $model = new Profile();
    $setter = 'set' . str_replace('_', '', ucwords($property, '_'));
    $model->$setter(null);
})->with($profileProperties)->throws(\InvalidArgumentException::class);

it('listInvalidProperties returns empty array (no required fields)', function () {
    $model = new Profile();
    expect($model->listInvalidProperties())->toBe([]);
});

it('valid returns true for default state', function () {
    $model = new Profile();
    expect($model->valid())->toBeTrue();
});

it('valid returns true after setting properties', function () {
    $model = new Profile(['company_name' => 'Test Co']);
    expect($model->valid())->toBeTrue();
});

it('implements ArrayAccess: offsetExists', function () {
    $model = new Profile(['company_name' => 'Test']);
    expect($model->offsetExists('company_name'))->toBeTrue();
    expect($model->offsetExists('nonexistent'))->toBeFalse();
});

it('implements ArrayAccess: offsetGet', function () {
    $model = new Profile(['company_name' => 'Test']);
    expect($model->offsetGet('company_name'))->toBe('Test');
    expect($model->offsetGet('nonexistent'))->toBeNull();
});

it('implements ArrayAccess: offsetSet with key', function () {
    $model = new Profile();
    $model->offsetSet('company_name', 'New Company');
    expect($model->offsetGet('company_name'))->toBe('New Company');
});

it('implements ArrayAccess: offsetSet without key (append)', function () {
    $model = new Profile();
    $model->offsetSet(null, 'extra_value');
    expect($model->offsetGet(0))->toBe('extra_value');
});

it('implements ArrayAccess: offsetUnset', function () {
    $model = new Profile(['company_name' => 'Test']);
    $model->offsetUnset('company_name');
    expect($model->offsetExists('company_name'))->toBeFalse();
});

it('jsonSerialize returns object with PascalCase keys', function () {
    $model = new Profile(['company_name' => 'Test Co', 'actual_format' => 'json']);
    $result = $model->jsonSerialize();
    expect($result)->toBeObject();
    expect($result->CompanyName)->toBe('Test Co');
    expect($result->ActualFormat)->toBe('json');
});

it('jsonSerialize omits null properties', function () {
    $model = new Profile(['company_name' => 'Test Co']);
    $result = $model->jsonSerialize();
    expect(property_exists($result, 'CompanyName'))->toBeTrue();
    expect(property_exists($result, 'ActualFormat'))->toBeFalse();
});

it('__toString returns JSON string with PascalCase keys', function () {
    $model = new Profile(['company_name' => 'Test Co']);
    $str = (string) $model;
    expect($str)->toBeString();
    $decoded = json_decode($str, true);
    expect($decoded['CompanyName'])->toBe('Test Co');
});

it('toHeaderValue returns compact JSON', function () {
    $model = new Profile(['company_name' => 'Test Co']);
    $value = $model->toHeaderValue();
    expect($value)->toBeString();
    expect(str_contains($value, "\n"))->toBeFalse();
});

it('isNullable returns false for all properties', function (string $property) {
    expect(Profile::isNullable($property))->toBeFalse();
})->with($profileProperties);

it('isNullable returns false for unknown property', function () {
    expect(Profile::isNullable('unknown'))->toBeFalse();
});

it('isNullableSetToNull returns false for set properties', function (string $property) {
    $value = $property === 'actual_usages' ? new ActualUsage() : 'some_value';
    $model = new Profile([$property => $value]);
    expect($model->isNullableSetToNull($property))->toBeFalse();
})->with($profileProperties);
