<?php

use Omisai\CreditOnline\Model\NegativeInfo;

it('has correct model name', function () {
    $info = new NegativeInfo();

    expect($info->getModelName())->toBe('NegativeInfo');
});

it('declares correct open API types', function () {
    $types = NegativeInfo::openAPITypes();

    expect($types)->toBe([
        'type' => 'string',
        'case_number' => 'string',
        'start' => '\DateTime',
        'end' => '\DateTime',
    ]);
});

it('declares correct open API formats', function () {
    $formats = NegativeInfo::openAPIFormats();

    expect($formats)->toBe([
        'type' => null,
        'case_number' => null,
        'start' => 'date',
        'end' => 'date',
    ]);
});

it('declares correct attribute map', function () {
    $map = NegativeInfo::attributeMap();

    expect($map)->toBe([
        'type' => 'Type',
        'case_number' => 'CaseNumber',
        'start' => 'Start',
        'end' => 'End',
    ]);
});

it('declares correct setters', function () {
    $setters = NegativeInfo::setters();

    expect($setters)->toBe([
        'type' => 'setType',
        'case_number' => 'setCaseNumber',
        'start' => 'setStart',
        'end' => 'setEnd',
    ]);
});

it('declares correct getters', function () {
    $getters = NegativeInfo::getters();

    expect($getters)->toBe([
        'type' => 'getType',
        'case_number' => 'getCaseNumber',
        'start' => 'getStart',
        'end' => 'getEnd',
    ]);
});

it('instantiates with empty constructor returning null property values', function () {
    $info = new NegativeInfo();

    expect($info->getType())->toBeNull();
    expect($info->getCaseNumber())->toBeNull();
    expect($info->getStart())->toBeNull();
    expect($info->getEnd())->toBeNull();
});

it('instantiates with data array setting property values', function () {
    $start = new DateTime('2023-01-01');
    $end = new DateTime('2023-06-30');
    $info = new NegativeInfo([
        'type' => 'Végrehajtás',
        'case_number' => '12345/2023',
        'start' => $start,
        'end' => $end,
    ]);

    expect($info->getType())->toBe('Végrehajtás');
    expect($info->getCaseNumber())->toBe('12345/2023');
    expect($info->getStart())->toBe($start);
    expect($info->getEnd())->toBe($end);
});

it('instantiates with partial data', function () {
    $info = new NegativeInfo([
        'type' => 'Végrehajtás',
        'case_number' => '12345/2023',
    ]);

    expect($info->getType())->toBe('Végrehajtás');
    expect($info->getCaseNumber())->toBe('12345/2023');
    expect($info->getStart())->toBeNull();
    expect($info->getEnd())->toBeNull();
});

it('handles constructor with null argument', function () {
    $info = new NegativeInfo(null);

    expect($info->getType())->toBeNull();
    expect($info->getCaseNumber())->toBeNull();
    expect($info->getStart())->toBeNull();
    expect($info->getEnd())->toBeNull();
});

it('sets and gets type', function (string $value) {
    $info = new NegativeInfo();
    $result = $info->setType($value);

    expect($result)->toBeInstanceOf(NegativeInfo::class);
    expect($info->getType())->toBe($value);
})->with([
    'enforcement' => 'Végrehajtás',
    'liquidation' => 'Felszámolás',
    'empty' => '',
]);

it('sets and gets case_number', function (string $value) {
    $info = new NegativeInfo();
    $result = $info->setCaseNumber($value);

    expect($result)->toBeInstanceOf(NegativeInfo::class);
    expect($info->getCaseNumber())->toBe($value);
})->with([
    'regular' => '12345/2023',
    'empty' => '',
]);

it('sets and gets start', function () {
    $info = new NegativeInfo();
    $start = new DateTime('2023-01-01');
    $result = $info->setStart($start);

    expect($result)->toBeInstanceOf(NegativeInfo::class);
    expect($info->getStart())->toBe($start);
});

it('sets and gets end', function () {
    $info = new NegativeInfo();
    $end = new DateTime('2023-06-30');
    $result = $info->setEnd($end);

    expect($result)->toBeInstanceOf(NegativeInfo::class);
    expect($info->getEnd())->toBe($end);
});

it('throws exception when setting null on non-nullable property', function (string $property, string $setter, mixed $validValue) {
    $info = new NegativeInfo();
    $info->{$setter}($validValue);

    expect(fn () => $info->{$setter}(null))->toThrow(
        \InvalidArgumentException::class,
        "non-nullable {$property} cannot be null"
    );
})->with([
    'type' => ['type', 'setType', 'Végrehajtás'],
    'case_number' => ['case_number', 'setCaseNumber', '12345/2023'],
    'start' => ['start', 'setStart', new DateTime('2023-01-01')],
    'end' => ['end', 'setEnd', new DateTime('2023-06-30')],
]);

it('declares no nullable properties', function () {
    expect(NegativeInfo::isNullable('type'))->toBeFalse();
    expect(NegativeInfo::isNullable('case_number'))->toBeFalse();
    expect(NegativeInfo::isNullable('start'))->toBeFalse();
    expect(NegativeInfo::isNullable('end'))->toBeFalse();
    expect(NegativeInfo::isNullable('nonexistent'))->toBeFalse();
});

it('returns false for isNullableSetToNull on non-nullable model', function () {
    $info = new NegativeInfo();

    expect($info->isNullableSetToNull('type'))->toBeFalse();
    expect($info->isNullableSetToNull('case_number'))->toBeFalse();
    expect($info->isNullableSetToNull('start'))->toBeFalse();
    expect($info->isNullableSetToNull('end'))->toBeFalse();
});

it('returns true for valid and empty invalid properties', function () {
    $info = new NegativeInfo();

    expect($info->valid())->toBeTrue();
    expect($info->listInvalidProperties())->toBe([]);
});

it('valid with all properties set', function () {
    $info = new NegativeInfo();
    $info->setType('Végrehajtás');
    $info->setCaseNumber('12345/2023');
    $info->setStart(new DateTime('2023-01-01'));
    $info->setEnd(new DateTime('2023-06-30'));

    expect($info->valid())->toBeTrue();
    expect($info->listInvalidProperties())->toBe([]);
});

it('implements ArrayAccess offsetExists', function () {
    $info = new NegativeInfo();

    expect($info->offsetExists('type'))->toBeFalse();

    $info->setType('Végrehajtás');
    expect($info->offsetExists('type'))->toBeTrue();
    expect($info->offsetExists('case_number'))->toBeFalse();
    expect($info->offsetExists('nonexistent'))->toBeFalse();
});

it('implements ArrayAccess offsetGet', function () {
    $info = new NegativeInfo();
    $info->setType('Végrehajtás');
    $info->setCaseNumber('12345/2023');

    expect($info->offsetGet('type'))->toBe('Végrehajtás');
    expect($info->offsetGet('case_number'))->toBe('12345/2023');
    expect($info->offsetGet('nonexistent'))->toBeNull();
});

it('implements ArrayAccess offsetSet', function () {
    $info = new NegativeInfo();

    $info->offsetSet('type', 'Végrehajtás');
    expect($info->getType())->toBe('Végrehajtás');

    $info->offsetSet('case_number', '12345/2023');
    expect($info->getCaseNumber())->toBe('12345/2023');

    $info->offsetSet(null, 'appended_value');
    expect($info->offsetGet(0))->toBe('appended_value');
});

it('implements ArrayAccess offsetUnset', function () {
    $info = new NegativeInfo();
    $info->setType('Végrehajtás');
    expect($info->offsetExists('type'))->toBeTrue();

    $info->offsetUnset('type');
    expect($info->offsetExists('type'))->toBeFalse();
});

it('serializes via jsonSerialize', function () {
    $info = new NegativeInfo();
    $info->setType('Végrehajtás');
    $info->setCaseNumber('12345/2023');

    $result = $info->jsonSerialize();

    expect($result)->toBeObject();
    expect($result->Type)->toBe('Végrehajtás');
    expect($result->CaseNumber)->toBe('12345/2023');
});

it('returns string representation via __toString', function () {
    $info = new NegativeInfo();
    $info->setType('Végrehajtás');
    $info->setCaseNumber('12345/2023');

    $str = (string) $info;

    expect($str)->toBeString();
    expect(strlen($str))->toBeGreaterThan(0);

    $decoded = json_decode($str, true);
    expect($decoded)->toBeArray();
    expect($decoded['Type'])->toBe('Végrehajtás');
    expect($decoded['CaseNumber'])->toBe('12345/2023');
});

it('returns header-safe presentation via toHeaderValue', function () {
    $info = new NegativeInfo();
    $info->setType('Végrehajtás');

    $header = $info->toHeaderValue();

    expect($header)->toBeString();
    expect(strlen($header))->toBeGreaterThan(0);

    $decoded = json_decode($header, true);
    expect($decoded)->toBeArray();
    expect($decoded['Type'])->toBe('Végrehajtás');
});

it('supports chaining setters', function () {
    $info = new NegativeInfo();
    $result = $info
        ->setType('Végrehajtás')
        ->setCaseNumber('12345/2023')
        ->setStart(new DateTime('2023-01-01'))
        ->setEnd(new DateTime('2023-06-30'));

    expect($result)->toBeInstanceOf(NegativeInfo::class);
    expect($info->getType())->toBe('Végrehajtás');
    expect($info->getCaseNumber())->toBe('12345/2023');
    expect($info->getStart())->toBeInstanceOf(DateTime::class);
    expect($info->getEnd())->toBeInstanceOf(DateTime::class);
});
