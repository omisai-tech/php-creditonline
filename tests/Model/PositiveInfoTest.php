<?php

use Omisai\CreditOnline\Model\PositiveInfo;

it('has correct model name', function () {
    $info = new PositiveInfo();

    expect($info->getModelName())->toBe('PositiveInfo');
});

it('declares correct open API types', function () {
    $types = PositiveInfo::openAPITypes();

    expect($types)->toBe([
        'type' => 'string',
        'start' => '\DateTime',
        'end' => '\DateTime',
    ]);
});

it('declares correct open API formats', function () {
    $formats = PositiveInfo::openAPIFormats();

    expect($formats)->toBe([
        'type' => null,
        'start' => 'date',
        'end' => 'date',
    ]);
});

it('declares correct attribute map', function () {
    $map = PositiveInfo::attributeMap();

    expect($map)->toBe([
        'type' => 'Type',
        'start' => 'Start',
        'end' => 'End',
    ]);
});

it('declares correct setters', function () {
    $setters = PositiveInfo::setters();

    expect($setters)->toBe([
        'type' => 'setType',
        'start' => 'setStart',
        'end' => 'setEnd',
    ]);
});

it('declares correct getters', function () {
    $getters = PositiveInfo::getters();

    expect($getters)->toBe([
        'type' => 'getType',
        'start' => 'getStart',
        'end' => 'getEnd',
    ]);
});

it('instantiates with empty constructor returning null property values', function () {
    $info = new PositiveInfo();

    expect($info->getType())->toBeNull();
    expect($info->getStart())->toBeNull();
    expect($info->getEnd())->toBeNull();
});

it('instantiates with data array setting property values', function () {
    $start = new DateTime('2023-01-01');
    $end = new DateTime('2023-12-31');
    $info = new PositiveInfo([
        'type' => 'ARBEV',
        'start' => $start,
        'end' => $end,
    ]);

    expect($info->getType())->toBe('ARBEV');
    expect($info->getStart())->toBe($start);
    expect($info->getEnd())->toBe($end);
});

it('instantiates with partial data', function () {
    $info = new PositiveInfo([
        'type' => 'ARBEV',
    ]);

    expect($info->getType())->toBe('ARBEV');
    expect($info->getStart())->toBeNull();
    expect($info->getEnd())->toBeNull();
});

it('handles constructor with null argument', function () {
    $info = new PositiveInfo(null);

    expect($info->getType())->toBeNull();
    expect($info->getStart())->toBeNull();
    expect($info->getEnd())->toBeNull();
});

it('sets and gets type', function (string $value) {
    $info = new PositiveInfo();
    $result = $info->setType($value);

    expect($result)->toBeInstanceOf(PositiveInfo::class);
    expect($info->getType())->toBe($value);
})->with([
    'arbev' => 'ARBEV',
    'empty' => '',
]);

it('sets and gets start', function () {
    $info = new PositiveInfo();
    $start = new DateTime('2023-01-01');
    $result = $info->setStart($start);

    expect($result)->toBeInstanceOf(PositiveInfo::class);
    expect($info->getStart())->toBe($start);
});

it('sets and gets end', function () {
    $info = new PositiveInfo();
    $end = new DateTime('2023-12-31');
    $result = $info->setEnd($end);

    expect($result)->toBeInstanceOf(PositiveInfo::class);
    expect($info->getEnd())->toBe($end);
});

it('throws exception when setting null on non-nullable property', function (string $property, string $setter, mixed $validValue) {
    $info = new PositiveInfo();
    $info->{$setter}($validValue);

    expect(fn () => $info->{$setter}(null))->toThrow(
        \InvalidArgumentException::class,
        "non-nullable {$property} cannot be null"
    );
})->with([
    'type' => ['type', 'setType', 'ARBEV'],
    'start' => ['start', 'setStart', new DateTime('2023-01-01')],
    'end' => ['end', 'setEnd', new DateTime('2023-12-31')],
]);

it('declares no nullable properties', function () {
    expect(PositiveInfo::isNullable('type'))->toBeFalse();
    expect(PositiveInfo::isNullable('start'))->toBeFalse();
    expect(PositiveInfo::isNullable('end'))->toBeFalse();
    expect(PositiveInfo::isNullable('nonexistent'))->toBeFalse();
});

it('returns false for isNullableSetToNull on non-nullable model', function () {
    $info = new PositiveInfo();

    expect($info->isNullableSetToNull('type'))->toBeFalse();
    expect($info->isNullableSetToNull('start'))->toBeFalse();
    expect($info->isNullableSetToNull('end'))->toBeFalse();
});

it('returns true for valid and empty invalid properties', function () {
    $info = new PositiveInfo();

    expect($info->valid())->toBeTrue();
    expect($info->listInvalidProperties())->toBe([]);
});

it('valid with all properties set', function () {
    $info = new PositiveInfo();
    $info->setType('ARBEV');
    $info->setStart(new DateTime('2023-01-01'));
    $info->setEnd(new DateTime('2023-12-31'));

    expect($info->valid())->toBeTrue();
    expect($info->listInvalidProperties())->toBe([]);
});

it('implements ArrayAccess offsetExists', function () {
    $info = new PositiveInfo();

    expect($info->offsetExists('type'))->toBeFalse();

    $info->setType('ARBEV');
    expect($info->offsetExists('type'))->toBeTrue();
    expect($info->offsetExists('start'))->toBeFalse();
    expect($info->offsetExists('nonexistent'))->toBeFalse();
});

it('implements ArrayAccess offsetGet', function () {
    $info = new PositiveInfo();
    $info->setType('ARBEV');
    $info->setStart(new DateTime('2023-01-01'));

    expect($info->offsetGet('type'))->toBe('ARBEV');
    expect($info->offsetGet('start'))->toBeInstanceOf(DateTime::class);
    expect($info->offsetGet('nonexistent'))->toBeNull();
});

it('implements ArrayAccess offsetSet', function () {
    $info = new PositiveInfo();

    $info->offsetSet('type', 'ARBEV');
    expect($info->getType())->toBe('ARBEV');

    $info->offsetSet('start', new DateTime('2023-01-01'));
    expect($info->getStart())->toBeInstanceOf(DateTime::class);

    $info->offsetSet(null, 'appended_value');
    expect($info->offsetGet(0))->toBe('appended_value');
});

it('implements ArrayAccess offsetUnset', function () {
    $info = new PositiveInfo();
    $info->setType('ARBEV');
    expect($info->offsetExists('type'))->toBeTrue();

    $info->offsetUnset('type');
    expect($info->offsetExists('type'))->toBeFalse();
});

it('serializes via jsonSerialize', function () {
    $info = new PositiveInfo();
    $info->setType('ARBEV');

    $result = $info->jsonSerialize();

    expect($result)->toBeObject();
    expect($result->Type)->toBe('ARBEV');
});

it('returns string representation via __toString', function () {
    $info = new PositiveInfo();
    $info->setType('ARBEV');

    $str = (string) $info;

    expect($str)->toBeString();
    expect(strlen($str))->toBeGreaterThan(0);

    $decoded = json_decode($str, true);
    expect($decoded)->toBeArray();
    expect($decoded['Type'])->toBe('ARBEV');
});

it('returns header-safe presentation via toHeaderValue', function () {
    $info = new PositiveInfo();
    $info->setType('ARBEV');

    $header = $info->toHeaderValue();

    expect($header)->toBeString();
    expect(strlen($header))->toBeGreaterThan(0);

    $decoded = json_decode($header, true);
    expect($decoded)->toBeArray();
    expect($decoded['Type'])->toBe('ARBEV');
});

it('supports chaining setters', function () {
    $info = new PositiveInfo();
    $result = $info
        ->setType('ARBEV')
        ->setStart(new DateTime('2023-01-01'))
        ->setEnd(new DateTime('2023-12-31'));

    expect($result)->toBeInstanceOf(PositiveInfo::class);
    expect($info->getType())->toBe('ARBEV');
    expect($info->getStart())->toBeInstanceOf(DateTime::class);
    expect($info->getEnd())->toBeInstanceOf(DateTime::class);
});
