<?php

use Omisai\CreditOnline\Model\FinancialSummary;

beforeEach(function () {
    $this->model = new FinancialSummary;
});

it('getModelName returns FinancialSummary', function () {
    expect($this->model->getModelName())->toBe('FinancialSummary');
});

it('openAPITypes returns correct type array', function () {
    $types = FinancialSummary::openAPITypes();
    expect($types)->toBe([
        'year' => 'int',
        'total_sales' => 'int',
        'income_from_operations' => 'int',
        'profit_after_taxation' => 'int',
        'subscribed_capital' => 'int',
        'shareholders_equity' => 'int',
    ]);
});

it('openAPIFormats returns all null formats', function () {
    $formats = FinancialSummary::openAPIFormats();
    expect($formats)->toHaveKeys(['year', 'total_sales', 'income_from_operations', 'profit_after_taxation', 'subscribed_capital', 'shareholders_equity']);
});

it('attributeMap uses correct original names', function (string $local, string $original) {
    $map = FinancialSummary::attributeMap();
    expect($map[$local])->toBe($original);
})->with([
    ['year', 'Year'],
    ['total_sales', 'TotalSales'],
    ['income_from_operations', 'IncomeFromOperations'],
    ['profit_after_taxation', 'ProfitAfterTaxation'],
    ['subscribed_capital', 'SubscribedCapital'],
    ['shareholders_equity', 'ShareholdersEquity'],
]);

it('setters returns correct mapping', function (string $property, string $setter) {
    expect(FinancialSummary::setters()[$property])->toBe($setter);
})->with([
    ['year', 'setYear'],
    ['total_sales', 'setTotalSales'],
    ['income_from_operations', 'setIncomeFromOperations'],
    ['profit_after_taxation', 'setProfitAfterTaxation'],
    ['subscribed_capital', 'setSubscribedCapital'],
    ['shareholders_equity', 'setShareholdersEquity'],
]);

it('getters returns correct mapping', function (string $property, string $getter) {
    expect(FinancialSummary::getters()[$property])->toBe($getter);
})->with([
    ['year', 'getYear'],
    ['total_sales', 'getTotalSales'],
    ['income_from_operations', 'getIncomeFromOperations'],
    ['profit_after_taxation', 'getProfitAfterTaxation'],
    ['subscribed_capital', 'getSubscribedCapital'],
    ['shareholders_equity', 'getShareholdersEquity'],
]);

it('setYear sets value and returns $this', function () {
    $result = $this->model->setYear(2023);
    expect($result)->toBe($this->model);
    expect($this->model->getYear())->toBe(2023);
});

it('setTotalSales sets value and returns $this', function () {
    $result = $this->model->setTotalSales(50000000);
    expect($result)->toBe($this->model);
    expect($this->model->getTotalSales())->toBe(50000000);
});

it('setIncomeFromOperations sets value and returns $this', function () {
    $result = $this->model->setIncomeFromOperations(10000000);
    expect($result)->toBe($this->model);
    expect($this->model->getIncomeFromOperations())->toBe(10000000);
});

it('setProfitAfterTaxation sets value and returns $this', function () {
    $result = $this->model->setProfitAfterTaxation(5000000);
    expect($result)->toBe($this->model);
    expect($this->model->getProfitAfterTaxation())->toBe(5000000);
});

it('setSubscribedCapital sets value and returns $this', function () {
    $result = $this->model->setSubscribedCapital(3000000);
    expect($result)->toBe($this->model);
    expect($this->model->getSubscribedCapital())->toBe(3000000);
});

it('setShareholdersEquity sets value and returns $this', function () {
    $result = $this->model->setShareholdersEquity(25000000);
    expect($result)->toBe($this->model);
    expect($this->model->getShareholdersEquity())->toBe(25000000);
});

it('setter throws on null for non-nullable properties', function (string $property) {
    $setters = FinancialSummary::setters();
    $setter = $setters[$property];
    $this->model->{$setter}(null);
})->throws(InvalidArgumentException::class)->with([
    ['year'],
    ['total_sales'],
    ['income_from_operations'],
    ['profit_after_taxation'],
    ['subscribed_capital'],
    ['shareholders_equity'],
]);

it('constructor with null sets all properties to null', function () {
    $model = new FinancialSummary;
    expect($model->getYear())->toBeNull();
    expect($model->getTotalSales())->toBeNull();
    expect($model->getIncomeFromOperations())->toBeNull();
    expect($model->getProfitAfterTaxation())->toBeNull();
    expect($model->getSubscribedCapital())->toBeNull();
    expect($model->getShareholdersEquity())->toBeNull();
});

it('constructor with data sets provided properties', function () {
    $model = new FinancialSummary([
        'year' => 2023,
        'total_sales' => 50000000,
        'income_from_operations' => 10000000,
        'profit_after_taxation' => 5000000,
        'subscribed_capital' => 3000000,
        'shareholders_equity' => 25000000,
    ]);
    expect($model->getYear())->toBe(2023);
    expect($model->getTotalSales())->toBe(50000000);
    expect($model->getIncomeFromOperations())->toBe(10000000);
    expect($model->getProfitAfterTaxation())->toBe(5000000);
    expect($model->getSubscribedCapital())->toBe(3000000);
    expect($model->getShareholdersEquity())->toBe(25000000);
});

it('constructor with partial data leaves others null', function () {
    $model = new FinancialSummary(['year' => 2024]);
    expect($model->getYear())->toBe(2024);
    expect($model->getTotalSales())->toBeNull();
});

it('constructor with empty array initializes all null', function () {
    $model = new FinancialSummary([]);
    expect($model->getYear())->toBeNull();
});

it('implements ArrayAccess: offsetExists', function () {
    $this->model->setYear(2023);
    expect($this->model->offsetExists('year'))->toBeTrue();
    expect($this->model->offsetExists('nonexistent'))->toBeFalse();
});

it('implements ArrayAccess: offsetGet', function () {
    $this->model->setYear(2023);
    expect($this->model->offsetGet('year'))->toBe(2023);
    expect($this->model->offsetGet('nonexistent'))->toBeNull();
});

it('implements ArrayAccess: offsetSet with key', function () {
    $this->model->offsetSet('year', 2021);
    expect($this->model->offsetGet('year'))->toBe(2021);
});

it('implements ArrayAccess: offsetSet without key', function () {
    $this->model->offsetSet(null, 'appended');
    expect($this->model->offsetGet(0))->toBe('appended');
});

it('implements ArrayAccess: offsetUnset', function () {
    $this->model->setYear(2023);
    $this->model->offsetUnset('year');
    expect($this->model->offsetExists('year'))->toBeFalse();
});

it('jsonSerialize returns array', function () {
    $this->model->setYear(2023);
    $serialized = $this->model->jsonSerialize();
    expect($serialized)->toBeObject();
});

it('__toString returns JSON string', function () {
    $this->model->setYear(2023);
    $str = $this->model->__toString();
    expect($str)->toBeString();
    expect($str)->toBeString()->not->toBeEmpty();
});

it('toHeaderValue returns JSON string', function () {
    $this->model->setYear(2023);
    $value = $this->model->toHeaderValue();
    expect($value)->toBeString();
    expect(json_decode($value, true))->toBeArray();
});

it('isNullable returns false for all properties', function (string $property) {
    expect(FinancialSummary::isNullable($property))->toBeFalse();
})->with(['year', 'total_sales', 'income_from_operations', 'profit_after_taxation', 'subscribed_capital', 'shareholders_equity']);

it('isNullable returns false for unknown property', function () {
    expect(FinancialSummary::isNullable('unknown'))->toBeFalse();
});

it('isNullableSetToNull initially returns false', function () {
    expect($this->model->isNullableSetToNull('year'))->toBeFalse();
    expect($this->model->isNullableSetToNull('total_sales'))->toBeFalse();
});

it('listInvalidProperties always returns empty', function () {
    expect($this->model->listInvalidProperties())->toBeEmpty();
});

it('valid always returns true', function () {
    expect($this->model->valid())->toBeTrue();
});
