<?php

use Omisai\CreditOnline\Model\Address;
use Omisai\CreditOnline\Model\Company;

$companyProperties = [
    'regnumber',
    'taxnumber',
    'name',
    'long_name',
    'headquarter',
    'status',
    'foundation',
    'funds',
    'employees',
    'last_turnover',
    'main_activity_code',
    'main_activity_description',
    'rating',
    'credit_limit',
    'industry',
    'type',
    'ksh_number',
    'eu_taxnumber',
    'link',
    'bank_accounts',
    'phones',
    'emails',
    'webpages',
    'negative_info',
    'positive_info',
    'financial_summaries',
    'signers',
    'members',
    'auditors',
    'sites',
    'has_deleted_tax_number',
    'has_active_positive_info',
    'has_active_negative_info',
    'is_koztartozasmentes',
    'is_megbizhato_adozo',
    'has_prohibited_member',
    'signer_change_in12_months',
    'member_change_in12_months',
    'headquarter_change_in12_months',
];

it('returns the model name', function () {
    $model = new Company();
    expect($model->getModelName())->toBe('Company');
});

it('has correct openAPITypes', function () use ($companyProperties) {
    $types = Company::openAPITypes();
    expect($types)->toBeArray()
        ->toHaveCount(count($companyProperties));
    expect($types['regnumber'])->toBe('string');
    expect($types['taxnumber'])->toBe('string');
    expect($types['name'])->toBe('string');
    expect($types['headquarter'])->toBe('\Omisai\CreditOnline\Model\Address');
    expect($types['foundation'])->toBe('\DateTime');
    expect($types['employees'])->toBe('int');
    expect($types['has_deleted_tax_number'])->toBe('bool');
    expect($types['negative_info'])->toBe('\Omisai\CreditOnline\Model\NegativeInfo[]');
    expect($types['bank_accounts'])->toBe('string[]');
});

it('has correct attributeMap', function () use ($companyProperties) {
    $map = Company::attributeMap();
    expect($map)->toBeArray()
        ->toHaveCount(count($companyProperties));
    expect($map['regnumber'])->toBe('Regnumber');
    expect($map['long_name'])->toBe('LongName');
    expect($map['is_megbizhato_adozo'])->toBe('IsMegbizhatoAdozo');
});

it('has correct setters mapping', function () use ($companyProperties) {
    $setters = Company::setters();
    expect($setters)->toBeArray()
        ->toHaveCount(count($companyProperties));
    expect($setters['regnumber'])->toBe('setRegnumber');
    expect($setters['taxnumber'])->toBe('setTaxnumber');
});

it('has correct getters mapping', function () use ($companyProperties) {
    $getters = Company::getters();
    expect($getters)->toBeArray()
        ->toHaveCount(count($companyProperties));
    expect($getters['regnumber'])->toBe('getRegnumber');
    expect($getters['taxnumber'])->toBe('getTaxnumber');
});

it('defaults all properties to null on construction without data', function () use ($companyProperties) {
    $model = new Company();
    foreach ($companyProperties as $prop) {
        $method = 'get' . str_replace('_', '', ucwords($prop, '_'));
        expect($model->$method())->toBeNull("property {$prop} should default to null");
    }
});

it('defaults all properties to null on construction with null', function () use ($companyProperties) {
    $model = new Company(null);
    foreach ($companyProperties as $prop) {
        $method = 'get' . str_replace('_', '', ucwords($prop, '_'));
        expect($model->$method())->toBeNull("property {$prop} should default to null");
    }
});

it('sets properties from construction data array', function () {
    $model = new Company([
        'regnumber' => '01-09-123456',
        'taxnumber' => '12345678-2-41',
        'name' => 'Test Company',
        'has_deleted_tax_number' => false,
    ]);
    expect($model->getRegnumber())->toBe('01-09-123456');
    expect($model->getTaxnumber())->toBe('12345678-2-41');
    expect($model->getName())->toBe('Test Company');
    expect($model->getHasDeletedTaxNumber())->toBeFalse();
});

it('getters and setters work correctly for string properties', function (string $property) {
    $model = new Company();
    $setter = 'set' . str_replace('_', '', ucwords($property, '_'));
    $getter = 'get' . str_replace('_', '', ucwords($property, '_'));
    $result = $model->$setter('test_value');
    expect($result)->toBe($model);
    expect($model->$getter())->toBe('test_value');
})->with([
    'regnumber',
    'taxnumber',
    'name',
    'long_name',
    'funds',
    'main_activity_code',
    'main_activity_description',
    'industry',
    'type',
    'ksh_number',
    'eu_taxnumber',
    'link',
]);

it('getters and setters work correctly for int properties', function (string $property) {
    $model = new Company();
    $setter = 'set' . str_replace('_', '', ucwords($property, '_'));
    $getter = 'get' . str_replace('_', '', ucwords($property, '_'));
    $result = $model->$setter(42);
    expect($result)->toBe($model);
    expect($model->$getter())->toBe(42);
})->with(['employees', 'last_turnover', 'rating', 'credit_limit']);

it('getters and setters work correctly for bool properties', function (string $property) {
    $model = new Company();
    $setter = 'set' . str_replace('_', '', ucwords($property, '_'));
    $getter = 'get' . str_replace('_', '', ucwords($property, '_'));
    $result = $model->$setter(true);
    expect($result)->toBe($model);
    expect($model->$getter())->toBeTrue();
})->with([
    'has_deleted_tax_number',
    'has_active_positive_info',
    'has_active_negative_info',
    'is_koztartozasmentes',
    'is_megbizhato_adozo',
    'has_prohibited_member',
    'signer_change_in12_months',
    'member_change_in12_months',
    'headquarter_change_in12_months',
]);

it('getters and setters work correctly for array properties', function (string $property) {
    $model = new Company();
    $setter = 'set' . str_replace('_', '', ucwords($property, '_'));
    $getter = 'get' . str_replace('_', '', ucwords($property, '_'));
    $value = ['item1', 'item2'];
    $result = $model->$setter($value);
    expect($result)->toBe($model);
    expect($model->$getter())->toBe($value);
})->with(['bank_accounts', 'phones', 'emails', 'webpages']);

it('getters and setters work correctly for complex object array properties', function (string $property) {
    $model = new Company();
    $setter = 'set' . str_replace('_', '', ucwords($property, '_'));
    $getter = 'get' . str_replace('_', '', ucwords($property, '_'));
    $value = [];
    $result = $model->$setter($value);
    expect($result)->toBe($model);
    expect($model->$getter())->toBe($value);
})->with(['negative_info', 'positive_info', 'financial_summaries', 'signers', 'members', 'auditors', 'sites']);

it('setHeadquarter accepts Address object', function () {
    $model = new Company();
    $address = new Address();
    $result = $model->setHeadquarter($address);
    expect($result)->toBe($model);
    expect($model->getHeadquarter())->toBe($address);
});

it('setFoundation accepts DateTime object', function () {
    $model = new Company();
    $date = new DateTime('2020-01-15');
    $result = $model->setFoundation($date);
    expect($result)->toBe($model);
    expect($model->getFoundation())->toBe($date);
});

it('non-nullable setters throw on null', function (string $property) {
    $model = new Company();
    $setter = 'set' . str_replace('_', '', ucwords($property, '_'));
    $model->$setter(null);
})->with($companyProperties)->throws(\InvalidArgumentException::class);

it('listInvalidProperties returns empty for valid default state', function () {
    $model = new Company();
    expect($model->listInvalidProperties())->toBe([]);
});

it('valid returns true for default state', function () {
    $model = new Company();
    expect($model->valid())->toBeTrue();
});

it('getStatusAllowableValues returns correct enum values', function () {
    $model = new Company();
    $values = $model->getStatusAllowableValues();
    expect($values)->toBeArray()
        ->toContain('Működő')
        ->toContain('Eljárás folyamatban')
        ->toContain('Megszűnt');
});

it('setStatus validates allowable values', function (string $status) {
    $model = new Company();
    $result = $model->setStatus($status);
    expect($result)->toBe($model);
    expect($model->getStatus())->toBe($status);
})->with([
    'Működő',
    'Eljárás folyamatban',
    'Megszűnt',
]);

it('setStatus throws on invalid value', function () {
    $model = new Company();
    $model->setStatus('InvalidStatus');
})->throws(\InvalidArgumentException::class);

it('implements ArrayAccess: offsetExists', function () {
    $model = new Company(['name' => 'Test']);
    expect($model->offsetExists('name'))->toBeTrue();
    expect($model->offsetExists('nonexistent'))->toBeFalse();
});

it('implements ArrayAccess: offsetGet', function () {
    $model = new Company(['name' => 'Test']);
    expect($model->offsetGet('name'))->toBe('Test');
    expect($model->offsetGet('nonexistent'))->toBeNull();
});

it('implements ArrayAccess: offsetSet with key', function () {
    $model = new Company();
    $model->offsetSet('name', 'New Name');
    expect($model->offsetGet('name'))->toBe('New Name');
});

it('implements ArrayAccess: offsetSet without key (append)', function () {
    $model = new Company();
    $model->offsetSet(null, 'extra');
    expect($model->offsetGet(0))->toBe('extra');
});

it('implements ArrayAccess: offsetUnset', function () {
    $model = new Company(['name' => 'Test']);
    $model->offsetUnset('name');
    expect($model->offsetExists('name'))->toBeFalse();
});

it('jsonSerialize returns object with PascalCase keys', function () {
    $model = new Company(['name' => 'Test Corp', 'employees' => 50]);
    $result = $model->jsonSerialize();
    expect($result)->toBeObject();
    expect($result->Name)->toBe('Test Corp');
    expect($result->Employees)->toBe(50);
});

it('jsonSerialize omits null properties', function () {
    $model = new Company(['name' => 'Test Corp']);
    $result = $model->jsonSerialize();
    expect(property_exists($result, 'Name'))->toBeTrue();
    expect(property_exists($result, 'Employees'))->toBeFalse();
});

it('__toString returns JSON string with PascalCase keys', function () {
    $model = new Company(['name' => 'Test Corp']);
    $str = (string) $model;
    expect($str)->toBeString();
    $decoded = json_decode($str, true);
    expect($decoded['Name'])->toBe('Test Corp');
});

it('toHeaderValue returns compact JSON', function () {
    $model = new Company(['name' => 'Test Corp']);
    $value = $model->toHeaderValue();
    expect($value)->toBeString();
    expect(str_contains($value, "\n"))->toBeFalse();
});

it('isNullable returns false for non-nullable properties', function (string $property) {
    expect(Company::isNullable($property))->toBeFalse();
})->with($companyProperties);

it('isNullable returns false for unknown property', function () {
    expect(Company::isNullable('unknown'))->toBeFalse();
});

it('isNullableSetToNull returns false for set properties', function (string $property) {
    $model = new Company([$property => 'some_value']);
    expect($model->isNullableSetToNull($property))->toBeFalse();
})->with(['regnumber', 'taxnumber', 'name']);

it('stores and retrieves headquarter_change_in12_months as bool', function () {
    $model = new Company();
    $model->setHeadquarterChangeIn12Months(true);
    expect($model->getHeadquarterChangeIn12Months())->toBeTrue();
    $model->setHeadquarterChangeIn12Months(false);
    expect($model->getHeadquarterChangeIn12Months())->toBeFalse();
});
