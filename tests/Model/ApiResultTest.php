<?php

use Omisai\CreditOnline\Model\ApiResult;
use Omisai\CreditOnline\Model\Company;

beforeEach(function () {
    $this->model = new ApiResult();
});

it('getModelName returns ApiResult', function () {
    expect($this->model->getModelName())->toBe('ApiResult');
});

it('openAPITypes returns correct type array', function () {
    $types = ApiResult::openAPITypes();
    expect($types)->toBeArray()
        ->toHaveKey('limit_reached', 'bool')
        ->toHaveKey('companies', '\Omisai\CreditOnline\Model\Company[]');
});

it('openAPIFormats returns correct format array', function () {
    $formats = ApiResult::openAPIFormats();
    expect($formats)->toBeArray()
        ->toHaveKeys(['limit_reached', 'companies']);
});

it('attributeMap maps local names to original names', function () {
    $map = ApiResult::attributeMap();
    expect($map)->toBe([
        'limit_reached' => 'LimitReached',
        'companies' => 'Companies',
    ]);
});

it('setters returns mapping of properties to setter methods', function () {
    $setters = ApiResult::setters();
    expect($setters)->toBe([
        'limit_reached' => 'setLimitReached',
        'companies' => 'setCompanies',
    ]);
});

it('getters returns mapping of properties to getter methods', function () {
    $getters = ApiResult::getters();
    expect($getters)->toBe([
        'limit_reached' => 'getLimitReached',
        'companies' => 'getCompanies',
    ]);
});

it('setLimitReached sets value and returns $this', function () {
    $result = $this->model->setLimitReached(true);
    expect($result)->toBe($this->model);
    expect($this->model->getLimitReached())->toBeTrue();
});

it('setLimitReached throws on null', function () {
    $this->model->setLimitReached(null);
})->throws(\InvalidArgumentException::class, 'non-nullable limit_reached cannot be null');

it('setCompanies sets value and returns $this', function () {
    $companies = [new Company()];
    $result = $this->model->setCompanies($companies);
    expect($result)->toBe($this->model);
    expect($this->model->getCompanies())->toBe($companies);
});

it('setCompanies throws on null', function () {
    $this->model->setCompanies(null);
})->throws(\InvalidArgumentException::class, 'non-nullable companies cannot be null');

it('constructor with null parameter initializes with null defaults', function () {
    $model = new ApiResult();
    expect($model->getLimitReached())->toBeNull();
    expect($model->getCompanies())->toBeNull();
});

it('constructor with data sets properties', function () {
    $companies = [new Company()];
    $model = new ApiResult([
        'limit_reached' => true,
        'companies' => $companies,
    ]);
    expect($model->getLimitReached())->toBeTrue();
    expect($model->getCompanies())->toBe($companies);
});

it('constructor with partial data sets only given properties', function () {
    $model = new ApiResult([
        'limit_reached' => false,
    ]);
    expect($model->getLimitReached())->toBeFalse();
    expect($model->getCompanies())->toBeNull();
});

it('implements ArrayAccess via offsetExists', function () {
    $this->model->setLimitReached(true);
    expect($this->model->offsetExists('limit_reached'))->toBeTrue();
    expect($this->model->offsetExists('nonexistent'))->toBeFalse();
});

it('implements ArrayAccess via offsetGet', function () {
    $this->model->setLimitReached(true);
    expect($this->model->offsetGet('limit_reached'))->toBeTrue();
    expect($this->model->offsetGet('nonexistent'))->toBeNull();
});

it('implements ArrayAccess via offsetSet with key', function () {
    $this->model->offsetSet('limit_reached', false);
    expect($this->model->offsetGet('limit_reached'))->toBeFalse();
});

it('implements ArrayAccess via offsetSet without key', function () {
    $this->model->offsetSet(null, 'value');
    expect($this->model->offsetGet(0))->toBe('value');
});

it('implements ArrayAccess via offsetUnset', function () {
    $this->model->setLimitReached(true);
    expect($this->model->offsetExists('limit_reached'))->toBeTrue();
    $this->model->offsetUnset('limit_reached');
    expect($this->model->offsetExists('limit_reached'))->toBeFalse();
});

it('jsonSerialize calls ObjectSerializer::sanitizeForSerialization', function () {
    $this->model->setLimitReached(true);
    $serialized = $this->model->jsonSerialize();
    expect($serialized)->toBeObject();
});

it('__toString returns pretty-printed JSON string', function () {
    $this->model->setLimitReached(true);
    $str = (string) $this->model;
    expect($str)->toBeString();
    expect($str)->toBeString()->not->toBeEmpty();
});

it('toHeaderValue returns compact JSON string', function () {
    $this->model->setLimitReached(true);
    $value = $this->model->toHeaderValue();
    expect($value)->toBeString();
    expect(json_decode($value, true))->toBeArray();
});

it('isNullable returns false for non-nullable properties', function (string $property) {
    expect(ApiResult::isNullable($property))->toBeFalse();
})->with(['limit_reached', 'companies']);

it('isNullable returns false for unknown properties', function () {
    expect(ApiResult::isNullable('unknown'))->toBeFalse();
});

it('isNullableSetToNull initially returns false', function () {
    expect($this->model->isNullableSetToNull('limit_reached'))->toBeFalse();
    expect($this->model->isNullableSetToNull('companies'))->toBeFalse();
});

it('listInvalidProperties catches null limit_reached', function () {
    $invalid = $this->model->listInvalidProperties();
    expect($invalid)->toBeArray();
    expect($invalid[0])->toContain("'limit_reached' can't be null");
});

it('valid returns false when limit_reached is null', function () {
    expect($this->model->valid())->toBeFalse();
});

it('valid returns true when limit_reached is set', function () {
    $this->model->setLimitReached(true);
    expect($this->model->valid())->toBeTrue();
});

it('getLimitReached returns bool when set', function () {
    $this->model->setLimitReached(false);
    expect($this->model->getLimitReached())->toBeFalse();
});
