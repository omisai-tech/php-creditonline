<?php

use Omisai\CreditOnline\Model\ApiResult;

it('returns the model name', function () {
    $model = new ApiResult();
    expect($model->getModelName())->toBe('ApiResult');
});

it('has correct openAPITypes', function () {
    $types = ApiResult::openAPITypes();
    expect($types)->toBeArray()
        ->toHaveKey('limit_reached')
        ->toHaveKey('companies');
    expect($types['limit_reached'])->toBe('bool');
    expect($types['companies'])->toBe('\Omisai\CreditOnline\Model\Company[]');
});

it('has correct openAPIFormats', function () {
    $formats = ApiResult::openAPIFormats();
    expect($formats)->toBeArray()
        ->toHaveKey('limit_reached')
        ->toHaveKey('companies');
    expect($formats['limit_reached'])->toBeNull();
    expect($formats['companies'])->toBeNull();
});

it('has correct attributeMap', function () {
    $map = ApiResult::attributeMap();
    expect($map)->toBeArray();
    expect($map['limit_reached'])->toBe('LimitReached');
    expect($map['companies'])->toBe('Companies');
});

it('has correct setters', function () {
    $setters = ApiResult::setters();
    expect($setters)->toBeArray();
    expect($setters['limit_reached'])->toBe('setLimitReached');
    expect($setters['companies'])->toBe('setCompanies');
});

it('has correct getters', function () {
    $getters = ApiResult::getters();
    expect($getters)->toBeArray();
    expect($getters['limit_reached'])->toBe('getLimitReached');
    expect($getters['companies'])->toBe('getCompanies');
});

it('defaults properties to null on construction with no data', function () {
    $model = new ApiResult();
    expect($model->getLimitReached())->toBeNull();
    expect($model->getCompanies())->toBeNull();
});

it('defaults properties to null on construction with empty array', function () {
    $model = new ApiResult([]);
    expect($model->getLimitReached())->toBeNull();
    expect($model->getCompanies())->toBeNull();
});

it('defaults properties to null on construction with null', function () {
    $model = new ApiResult(null);
    expect($model->getLimitReached())->toBeNull();
    expect($model->getCompanies())->toBeNull();
});

it('sets properties from construction data array', function () {
    $model = new ApiResult([
        'limit_reached' => true,
        'companies' => [],
    ]);
    expect($model->getLimitReached())->toBeTrue();
    expect($model->getCompanies())->toBe([]);
});

it('sets limit_reached and returns self (fluid interface)', function () {
    $model = new ApiResult();
    $result = $model->setLimitReached(true);
    expect($result)->toBe($model);
    expect($model->getLimitReached())->toBeTrue();
});

it('sets companies and returns self (fluid interface)', function () {
    $model = new ApiResult();
    $result = $model->setCompanies([]);
    expect($result)->toBe($model);
    expect($model->getCompanies())->toBe([]);
});

it('setLimitReached throws on null for non-nullable property', function () {
    $model = new ApiResult();
    $model->setLimitReached(null);
})->throws(\InvalidArgumentException::class, 'non-nullable limit_reached cannot be null');

it('setCompanies throws on null for non-nullable property', function () {
    $model = new ApiResult();
    $model->setCompanies(null);
})->throws(\InvalidArgumentException::class, 'non-nullable companies cannot be null');

it('listInvalidProperties returns error when limit_reached is null', function () {
    $model = new ApiResult();
    $invalid = $model->listInvalidProperties();
    expect($invalid)->toBeArray();
    expect($invalid)->toContain("'limit_reached' can't be null");
});

it('listInvalidProperties returns empty when limit_reached is set', function () {
    $model = (new ApiResult())->setLimitReached(false);
    expect($model->listInvalidProperties())->toBe([]);
});

it('valid returns false when limit_reached is null', function () {
    $model = new ApiResult();
    expect($model->valid())->toBeFalse();
});

it('valid returns true when limit_reached is set', function () {
    $model = (new ApiResult())->setLimitReached(false);
    expect($model->valid())->toBeTrue();
});

it('implements ArrayAccess: offsetExists', function () {
    $model = new ApiResult(['limit_reached' => true]);
    expect($model->offsetExists('limit_reached'))->toBeTrue();
    expect($model->offsetExists('nonexistent'))->toBeFalse();
});

it('implements ArrayAccess: offsetGet', function () {
    $model = new ApiResult(['limit_reached' => true]);
    expect($model->offsetGet('limit_reached'))->toBeTrue();
    expect($model->offsetGet('nonexistent'))->toBeNull();
});

it('implements ArrayAccess: offsetSet with key', function () {
    $model = new ApiResult();
    $model->offsetSet('limit_reached', false);
    expect($model->offsetGet('limit_reached'))->toBeFalse();
});

it('implements ArrayAccess: offsetSet without key (append)', function () {
    $model = new ApiResult();
    $model->offsetSet(null, 'appended_value');
    expect($model->offsetGet(0))->toBe('appended_value');
});

it('implements ArrayAccess: offsetUnset', function () {
    $model = new ApiResult(['limit_reached' => true]);
    expect($model->offsetExists('limit_reached'))->toBeTrue();
    $model->offsetUnset('limit_reached');
    expect($model->offsetExists('limit_reached'))->toBeFalse();
});

it('jsonSerialize returns object with PascalCase keys', function () {
    $model = new ApiResult(['limit_reached' => false]);
    $result = $model->jsonSerialize();
    expect($result)->toBeObject();
    expect($result->LimitReached)->toBeFalse();
});

it('jsonSerialize omits null properties', function () {
    $model = new ApiResult(['limit_reached' => true]);
    $result = $model->jsonSerialize();
    expect(property_exists($result, 'LimitReached'))->toBeTrue();
    expect(property_exists($result, 'Companies'))->toBeFalse();
});

it('__toString returns JSON string with PascalCase keys', function () {
    $model = new ApiResult(['limit_reached' => false]);
    $str = (string) $model;
    expect($str)->toBeString();
    $decoded = json_decode($str, true);
    expect($decoded)->toBeArray();
    expect($decoded['LimitReached'])->toBeFalse();
});

it('toHeaderValue returns compact JSON', function () {
    $model = new ApiResult(['limit_reached' => false]);
    $value = $model->toHeaderValue();
    expect($value)->toBeString();
    expect(str_contains($value, "\n"))->toBeFalse();
    expect(json_decode($value, true)['LimitReached'])->toBeFalse();
});

it('isNullable returns false for non-nullable properties', function (string $property) {
    expect(ApiResult::isNullable($property))->toBeFalse();
})->with(['limit_reached', 'companies']);

it('isNullable returns false for unknown property', function () {
    expect(ApiResult::isNullable('unknown'))->toBeFalse();
});

it('isNullableSetToNull returns false for non-null properties', function (string $property) {
    $model = new ApiResult([$property => 'some_value']);
    expect($model->isNullableSetToNull($property))->toBeFalse();
})->with(['limit_reached', 'companies']);
