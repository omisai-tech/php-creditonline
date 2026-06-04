<?php

use Omisai\CreditOnline\Model\FinancialSummary;

it('has correct model name', function () {
    $summary = new FinancialSummary();

    expect($summary->getModelName())->toBe('FinancialSummary');
});

it('declares correct open API types', function () {
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

it('declares correct open API formats', function () {
    $formats = FinancialSummary::openAPIFormats();

    expect($formats)->toBe([
        'year' => null,
        'total_sales' => null,
        'income_from_operations' => null,
        'profit_after_taxation' => null,
        'subscribed_capital' => null,
        'shareholders_equity' => null,
    ]);
});

it('declares correct attribute map', function () {
    $map = FinancialSummary::attributeMap();

    expect($map)->toBe([
        'year' => 'Year',
        'total_sales' => 'TotalSales',
        'income_from_operations' => 'IncomeFromOperations',
        'profit_after_taxation' => 'ProfitAfterTaxation',
        'subscribed_capital' => 'SubscribedCapital',
        'shareholders_equity' => 'ShareholdersEquity',
    ]);
});

it('declares correct setters', function () {
    $setters = FinancialSummary::setters();

    expect($setters)->toBe([
        'year' => 'setYear',
        'total_sales' => 'setTotalSales',
        'income_from_operations' => 'setIncomeFromOperations',
        'profit_after_taxation' => 'setProfitAfterTaxation',
        'subscribed_capital' => 'setSubscribedCapital',
        'shareholders_equity' => 'setShareholdersEquity',
    ]);
});

it('declares correct getters', function () {
    $getters = FinancialSummary::getters();

    expect($getters)->toBe([
        'year' => 'getYear',
        'total_sales' => 'getTotalSales',
        'income_from_operations' => 'getIncomeFromOperations',
        'profit_after_taxation' => 'getProfitAfterTaxation',
        'subscribed_capital' => 'getSubscribedCapital',
        'shareholders_equity' => 'getShareholdersEquity',
    ]);
});

it('instantiates with empty constructor returning null property values', function () {
    $summary = new FinancialSummary();

    expect($summary->getYear())->toBeNull();
    expect($summary->getTotalSales())->toBeNull();
    expect($summary->getIncomeFromOperations())->toBeNull();
    expect($summary->getProfitAfterTaxation())->toBeNull();
    expect($summary->getSubscribedCapital())->toBeNull();
    expect($summary->getShareholdersEquity())->toBeNull();
});

it('instantiates with data array setting property values', function () {
    $summary = new FinancialSummary([
        'year' => 2023,
        'total_sales' => 50000000,
        'income_from_operations' => 3500000,
        'profit_after_taxation' => 2800000,
        'subscribed_capital' => 10000000,
        'shareholders_equity' => 15000000,
    ]);

    expect($summary->getYear())->toBe(2023);
    expect($summary->getTotalSales())->toBe(50000000);
    expect($summary->getIncomeFromOperations())->toBe(3500000);
    expect($summary->getProfitAfterTaxation())->toBe(2800000);
    expect($summary->getSubscribedCapital())->toBe(10000000);
    expect($summary->getShareholdersEquity())->toBe(15000000);
});

it('instantiates with partial data including zero values', function () {
    $summary = new FinancialSummary([
        'year' => 2023,
        'total_sales' => 0,
        'profit_after_taxation' => -5000000,
    ]);

    expect($summary->getYear())->toBe(2023);
    expect($summary->getTotalSales())->toBe(0);
    expect($summary->getProfitAfterTaxation())->toBe(-5000000);
    expect($summary->getIncomeFromOperations())->toBeNull();
    expect($summary->getSubscribedCapital())->toBeNull();
    expect($summary->getShareholdersEquity())->toBeNull();
});

it('handles constructor with null argument', function () {
    $summary = new FinancialSummary(null);

    expect($summary->getYear())->toBeNull();
    expect($summary->getTotalSales())->toBeNull();
    expect($summary->getIncomeFromOperations())->toBeNull();
    expect($summary->getProfitAfterTaxation())->toBeNull();
    expect($summary->getSubscribedCapital())->toBeNull();
    expect($summary->getShareholdersEquity())->toBeNull();
});

it('sets and gets year', function (int $value) {
    $summary = new FinancialSummary();
    $result = $summary->setYear($value);

    expect($result)->toBeInstanceOf(FinancialSummary::class);
    expect($summary->getYear())->toBe($value);
})->with([
    '2023' => 2023,
    '2022' => 2022,
    'zero' => 0,
    'negative' => -1,
]);

it('sets and gets total_sales', function (int $value) {
    $summary = new FinancialSummary();
    $result = $summary->setTotalSales($value);

    expect($result)->toBeInstanceOf(FinancialSummary::class);
    expect($summary->getTotalSales())->toBe($value);
})->with([
    'positive' => 50000000,
    'zero' => 0,
    'negative' => -1000000,
]);

it('sets and gets income_from_operations', function (int $value) {
    $summary = new FinancialSummary();
    $result = $summary->setIncomeFromOperations($value);

    expect($result)->toBeInstanceOf(FinancialSummary::class);
    expect($summary->getIncomeFromOperations())->toBe($value);
})->with([
    'positive' => 3500000,
    'zero' => 0,
    'negative' => -2000000,
]);

it('sets and gets profit_after_taxation', function (int $value) {
    $summary = new FinancialSummary();
    $result = $summary->setProfitAfterTaxation($value);

    expect($result)->toBeInstanceOf(FinancialSummary::class);
    expect($summary->getProfitAfterTaxation())->toBe($value);
})->with([
    'positive' => 2800000,
    'zero' => 0,
    'negative' => -5000000,
]);

it('sets and gets subscribed_capital', function (int $value) {
    $summary = new FinancialSummary();
    $result = $summary->setSubscribedCapital($value);

    expect($result)->toBeInstanceOf(FinancialSummary::class);
    expect($summary->getSubscribedCapital())->toBe($value);
})->with([
    'positive' => 10000000,
    'zero' => 0,
]);

it('sets and gets shareholders_equity', function (int $value) {
    $summary = new FinancialSummary();
    $result = $summary->setShareholdersEquity($value);

    expect($result)->toBeInstanceOf(FinancialSummary::class);
    expect($summary->getShareholdersEquity())->toBe($value);
})->with([
    'positive' => 15000000,
    'zero' => 0,
    'negative' => -3000000,
]);

it('throws exception when setting null on non-nullable property', function (string $property, string $setter, int $validValue) {
    $summary = new FinancialSummary();
    $summary->{$setter}($validValue);

    expect(fn () => $summary->{$setter}(null))->toThrow(
        \InvalidArgumentException::class,
        "non-nullable {$property} cannot be null"
    );
})->with([
    'year' => ['year', 'setYear', 2023],
    'total_sales' => ['total_sales', 'setTotalSales', 50000000],
    'income_from_operations' => ['income_from_operations', 'setIncomeFromOperations', 3500000],
    'profit_after_taxation' => ['profit_after_taxation', 'setProfitAfterTaxation', 2800000],
    'subscribed_capital' => ['subscribed_capital', 'setSubscribedCapital', 10000000],
    'shareholders_equity' => ['shareholders_equity', 'setShareholdersEquity', 15000000],
]);

it('declares no nullable properties', function () {
    expect(FinancialSummary::isNullable('year'))->toBeFalse();
    expect(FinancialSummary::isNullable('total_sales'))->toBeFalse();
    expect(FinancialSummary::isNullable('income_from_operations'))->toBeFalse();
    expect(FinancialSummary::isNullable('profit_after_taxation'))->toBeFalse();
    expect(FinancialSummary::isNullable('subscribed_capital'))->toBeFalse();
    expect(FinancialSummary::isNullable('shareholders_equity'))->toBeFalse();
    expect(FinancialSummary::isNullable('nonexistent'))->toBeFalse();
});

it('returns false for isNullableSetToNull on non-nullable model', function () {
    $summary = new FinancialSummary();

    expect($summary->isNullableSetToNull('year'))->toBeFalse();
    expect($summary->isNullableSetToNull('total_sales'))->toBeFalse();
    expect($summary->isNullableSetToNull('income_from_operations'))->toBeFalse();
    expect($summary->isNullableSetToNull('profit_after_taxation'))->toBeFalse();
    expect($summary->isNullableSetToNull('subscribed_capital'))->toBeFalse();
    expect($summary->isNullableSetToNull('shareholders_equity'))->toBeFalse();
});

it('returns true for valid and empty invalid properties', function () {
    $summary = new FinancialSummary();

    expect($summary->valid())->toBeTrue();
    expect($summary->listInvalidProperties())->toBe([]);
});

it('valid with all properties set', function () {
    $summary = new FinancialSummary();
    $summary->setYear(2023);
    $summary->setTotalSales(50000000);
    $summary->setIncomeFromOperations(3500000);
    $summary->setProfitAfterTaxation(2800000);
    $summary->setSubscribedCapital(10000000);
    $summary->setShareholdersEquity(15000000);

    expect($summary->valid())->toBeTrue();
    expect($summary->listInvalidProperties())->toBe([]);
});

it('implements ArrayAccess offsetExists', function () {
    $summary = new FinancialSummary();

    expect($summary->offsetExists('year'))->toBeFalse();

    $summary->setYear(2023);
    expect($summary->offsetExists('year'))->toBeTrue();
    expect($summary->offsetExists('total_sales'))->toBeFalse();
    expect($summary->offsetExists('nonexistent'))->toBeFalse();
});

it('implements ArrayAccess offsetGet', function () {
    $summary = new FinancialSummary();
    $summary->setYear(2023);
    $summary->setTotalSales(50000000);

    expect($summary->offsetGet('year'))->toBe(2023);
    expect($summary->offsetGet('total_sales'))->toBe(50000000);
    expect($summary->offsetGet('nonexistent'))->toBeNull();
});

it('implements ArrayAccess offsetSet', function () {
    $summary = new FinancialSummary();

    $summary->offsetSet('year', 2023);
    expect($summary->getYear())->toBe(2023);

    $summary->offsetSet('total_sales', 50000000);
    expect($summary->getTotalSales())->toBe(50000000);

    $summary->offsetSet(null, 999);
    expect($summary->offsetGet(0))->toBe(999);
});

it('implements ArrayAccess offsetUnset', function () {
    $summary = new FinancialSummary();
    $summary->setYear(2023);
    expect($summary->offsetExists('year'))->toBeTrue();

    $summary->offsetUnset('year');
    expect($summary->offsetExists('year'))->toBeFalse();
});

it('serializes via jsonSerialize', function () {
    $summary = new FinancialSummary();
    $summary->setYear(2023);
    $summary->setTotalSales(50000000);
    $summary->setProfitAfterTaxation(2800000);

    $result = $summary->jsonSerialize();

    expect($result)->toBeObject();
    expect($result->Year)->toBe(2023);
    expect($result->TotalSales)->toBe(50000000);
    expect($result->ProfitAfterTaxation)->toBe(2800000);
});

it('returns string representation via __toString', function () {
    $summary = new FinancialSummary();
    $summary->setYear(2023);
    $summary->setTotalSales(50000000);

    $str = (string) $summary;

    expect($str)->toBeString();
    expect(strlen($str))->toBeGreaterThan(0);

    $decoded = json_decode($str, true);
    expect($decoded)->toBeArray();
    expect($decoded['Year'])->toBe(2023);
    expect($decoded['TotalSales'])->toBe(50000000);
});

it('returns header-safe presentation via toHeaderValue', function () {
    $summary = new FinancialSummary();
    $summary->setYear(2023);

    $header = $summary->toHeaderValue();

    expect($header)->toBeString();
    expect(strlen($header))->toBeGreaterThan(0);

    $decoded = json_decode($header, true);
    expect($decoded)->toBeArray();
    expect($decoded['Year'])->toBe(2023);
});

it('supports chaining setters', function () {
    $summary = new FinancialSummary();
    $result = $summary
        ->setYear(2023)
        ->setTotalSales(50000000)
        ->setIncomeFromOperations(3500000)
        ->setProfitAfterTaxation(2800000)
        ->setSubscribedCapital(10000000)
        ->setShareholdersEquity(15000000);

    expect($result)->toBeInstanceOf(FinancialSummary::class);
    expect($summary->getYear())->toBe(2023);
    expect($summary->getTotalSales())->toBe(50000000);
    expect($summary->getIncomeFromOperations())->toBe(3500000);
    expect($summary->getProfitAfterTaxation())->toBe(2800000);
    expect($summary->getSubscribedCapital())->toBe(10000000);
    expect($summary->getShareholdersEquity())->toBe(15000000);
});
