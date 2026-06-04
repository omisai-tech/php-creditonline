<?php

use Omisai\CreditOnline\Model\Address;
use Omisai\CreditOnline\Model\Auditor;
use Omisai\CreditOnline\Model\Company;
use Omisai\CreditOnline\Model\FinancialSummary;
use Omisai\CreditOnline\Model\Member;
use Omisai\CreditOnline\Model\NegativeInfo;
use Omisai\CreditOnline\Model\PositiveInfo;
use Omisai\CreditOnline\Model\Signer;

beforeEach(function () {
    $this->model = new Company;
});

it('getModelName returns Company', function () {
    expect($this->model->getModelName())->toBe('Company');
});

it('openAPITypes returns all property types', function () {
    $types = Company::openAPITypes();
    expect($types)->toBeArray()
        ->toHaveKey('regnumber', 'string')
        ->toHaveKey('name', 'string')
        ->toHaveKey('headquarter', '\Omisai\CreditOnline\Model\Address')
        ->toHaveKey('status', 'string')
        ->toHaveKey('foundation', '\DateTime')
        ->toHaveKey('employees', 'int')
        ->toHaveKey('has_deleted_tax_number', 'bool')
        ->toHaveKey('signers', '\Omisai\CreditOnline\Model\Signer[]')
        ->toHaveKey('negative_info', '\Omisai\CreditOnline\Model\NegativeInfo[]');
});

it('openAPIFormats returns format array with date for foundation', function () {
    $formats = Company::openAPIFormats();
    expect($formats['foundation'])->toBe('date');
    expect($formats['regnumber'])->toBeNull();
});

it('attributeMap maps all local names to original names', function (string $local, string $original) {
    $map = Company::attributeMap();
    expect($map[$local])->toBe($original);
})->with([
    ['regnumber', 'Regnumber'],
    ['taxnumber', 'Taxnumber'],
    ['name', 'Name'],
    ['long_name', 'LongName'],
    ['headquarter', 'Headquarter'],
    ['status', 'Status'],
    ['foundation', 'Foundation'],
    ['funds', 'Funds'],
    ['employees', 'Employees'],
    ['last_turnover', 'LastTurnover'],
    ['main_activity_code', 'MainActivityCode'],
    ['main_activity_description', 'MainActivityDescription'],
    ['rating', 'Rating'],
    ['credit_limit', 'CreditLimit'],
    ['industry', 'Industry'],
    ['type', 'Type'],
    ['ksh_number', 'KSHNumber'],
    ['eu_taxnumber', 'EUTaxnumber'],
    ['link', 'Link'],
    ['bank_accounts', 'BankAccounts'],
    ['phones', 'Phones'],
    ['emails', 'Emails'],
    ['webpages', 'Webpages'],
    ['negative_info', 'NegativeInfo'],
    ['positive_info', 'PositiveInfo'],
    ['financial_summaries', 'FinancialSummaries'],
    ['signers', 'Signers'],
    ['members', 'Members'],
    ['auditors', 'Auditors'],
    ['sites', 'Sites'],
    ['has_deleted_tax_number', 'HasDeletedTaxNumber'],
    ['has_active_positive_info', 'HasActivePositiveInfo'],
    ['has_active_negative_info', 'HasActiveNegativeInfo'],
    ['is_koztartozasmentes', 'IsKoztartozasmentes'],
    ['is_megbizhato_adozo', 'IsMegbizhatoAdozo'],
    ['has_prohibited_member', 'HasProhibitedMember'],
    ['signer_change_in12_months', 'SignerChangeIn12Months'],
    ['member_change_in12_months', 'MemberChangeIn12Months'],
    ['headquarter_change_in12_months', 'HeadquarterChangeIn12Months'],
]);

it('setters returns mapping for all properties', function (string $property, string $setter) {
    $setters = Company::setters();
    expect($setters[$property])->toBe($setter);
})->with([
    ['regnumber', 'setRegnumber'],
    ['taxnumber', 'setTaxnumber'],
    ['name', 'setName'],
    ['long_name', 'setLongName'],
    ['headquarter', 'setHeadquarter'],
    ['status', 'setStatus'],
    ['foundation', 'setFoundation'],
    ['funds', 'setFunds'],
    ['employees', 'setEmployees'],
    ['last_turnover', 'setLastTurnover'],
    ['main_activity_code', 'setMainActivityCode'],
    ['main_activity_description', 'setMainActivityDescription'],
    ['rating', 'setRating'],
    ['credit_limit', 'setCreditLimit'],
    ['industry', 'setIndustry'],
    ['type', 'setType'],
    ['ksh_number', 'setKshNumber'],
    ['eu_taxnumber', 'setEuTaxnumber'],
    ['link', 'setLink'],
    ['bank_accounts', 'setBankAccounts'],
    ['phones', 'setPhones'],
    ['emails', 'setEmails'],
    ['webpages', 'setWebpages'],
    ['negative_info', 'setNegativeInfo'],
    ['positive_info', 'setPositiveInfo'],
    ['financial_summaries', 'setFinancialSummaries'],
    ['signers', 'setSigners'],
    ['members', 'setMembers'],
    ['auditors', 'setAuditors'],
    ['sites', 'setSites'],
    ['has_deleted_tax_number', 'setHasDeletedTaxNumber'],
    ['has_active_positive_info', 'setHasActivePositiveInfo'],
    ['has_active_negative_info', 'setHasActiveNegativeInfo'],
    ['is_koztartozasmentes', 'setIsKoztartozasmentes'],
    ['is_megbizhato_adozo', 'setIsMegbizhatoAdozo'],
    ['has_prohibited_member', 'setHasProhibitedMember'],
    ['signer_change_in12_months', 'setSignerChangeIn12Months'],
    ['member_change_in12_months', 'setMemberChangeIn12Months'],
    ['headquarter_change_in12_months', 'setHeadquarterChangeIn12Months'],
]);

it('getters returns mapping for all properties', function (string $property, string $getter) {
    $getters = Company::getters();
    expect($getters[$property])->toBe($getter);
})->with([
    ['regnumber', 'getRegnumber'],
    ['name', 'getName'],
    ['status', 'getStatus'],
    ['employees', 'getEmployees'],
    ['has_deleted_tax_number', 'getHasDeletedTaxNumber'],
    ['signers', 'getSigners'],
    ['headquarter', 'getHeadquarter'],
    ['foundation', 'getFoundation'],
]);

it('setRegnumber sets string value and returns $this', function () {
    $result = $this->model->setRegnumber('01-09-123456');
    expect($result)->toBe($this->model);
    expect($this->model->getRegnumber())->toBe('01-09-123456');
});

it('setTaxnumber sets string value and returns $this', function () {
    $result = $this->model->setTaxnumber('12345678-2-42');
    expect($result)->toBe($this->model);
    expect($this->model->getTaxnumber())->toBe('12345678-2-42');
});

it('setName sets string value and returns $this', function () {
    $result = $this->model->setName('Test Company');
    expect($result)->toBe($this->model);
    expect($this->model->getName())->toBe('Test Company');
});

it('setLongName sets string value and returns $this', function () {
    $result = $this->model->setLongName('Test Company Ltd.');
    expect($result)->toBe($this->model);
    expect($this->model->getLongName())->toBe('Test Company Ltd.');
});

it('setHeadquarter sets Address value and returns $this', function () {
    $address = new Address;
    $result = $this->model->setHeadquarter($address);
    expect($result)->toBe($this->model);
    expect($this->model->getHeadquarter())->toBe($address);
});

it('setStatus sets valid enum value and returns $this', function () {
    $result = $this->model->setStatus('Működő');
    expect($result)->toBe($this->model);
    expect($this->model->getStatus())->toBe('Működő');
});

it('setStatus throws on invalid enum value', function () {
    $this->model->setStatus('InvalidStatus');
})->throws(InvalidArgumentException::class);

it('setFoundation sets DateTime value and returns $this', function () {
    $date = new DateTime('2020-01-15');
    $result = $this->model->setFoundation($date);
    expect($result)->toBe($this->model);
    expect($this->model->getFoundation())->toBe($date);
});

it('setFunds sets string value and returns $this', function () {
    $result = $this->model->setFunds('3000000');
    expect($result)->toBe($this->model);
    expect($this->model->getFunds())->toBe('3000000');
});

it('setEmployees sets int value and returns $this', function () {
    $result = $this->model->setEmployees(42);
    expect($result)->toBe($this->model);
    expect($this->model->getEmployees())->toBe(42);
});

it('setLastTurnover sets int value and returns $this', function () {
    $result = $this->model->setLastTurnover(1000000);
    expect($result)->toBe($this->model);
    expect($this->model->getLastTurnover())->toBe(1000000);
});

it('setMainActivityCode sets string value and returns $this', function () {
    $result = $this->model->setMainActivityCode('6201');
    expect($result)->toBe($this->model);
    expect($this->model->getMainActivityCode())->toBe('6201');
});

it('setMainActivityDescription sets string value and returns $this', function () {
    $result = $this->model->setMainActivityDescription('Programozás');
    expect($result)->toBe($this->model);
    expect($this->model->getMainActivityDescription())->toBe('Programozás');
});

it('setRating sets int value and returns $this', function () {
    $result = $this->model->setRating(5);
    expect($result)->toBe($this->model);
    expect($this->model->getRating())->toBe(5);
});

it('setCreditLimit sets int value and returns $this', function () {
    $result = $this->model->setCreditLimit(1000000);
    expect($result)->toBe($this->model);
    expect($this->model->getCreditLimit())->toBe(1000000);
});

it('setIndustry sets string value and returns $this', function () {
    $result = $this->model->setIndustry('IT');
    expect($result)->toBe($this->model);
    expect($this->model->getIndustry())->toBe('IT');
});

it('setType sets string value and returns $this', function () {
    $result = $this->model->setType('Kft.');
    expect($result)->toBe($this->model);
    expect($this->model->getType())->toBe('Kft.');
});

it('setKshNumber sets string value and returns $this', function () {
    $result = $this->model->setKshNumber('12345678');
    expect($result)->toBe($this->model);
    expect($this->model->getKshNumber())->toBe('12345678');
});

it('setEuTaxnumber sets string value and returns $this', function () {
    $result = $this->model->setEuTaxnumber('HU12345678');
    expect($result)->toBe($this->model);
    expect($this->model->getEuTaxnumber())->toBe('HU12345678');
});

it('setLink sets string value and returns $this', function () {
    $result = $this->model->setLink('https://example.com/company');
    expect($result)->toBe($this->model);
    expect($this->model->getLink())->toBe('https://example.com/company');
});

it('setBankAccounts sets array value and returns $this', function () {
    $accounts = ['11111111-22222222', '33333333-44444444'];
    $result = $this->model->setBankAccounts($accounts);
    expect($result)->toBe($this->model);
    expect($this->model->getBankAccounts())->toBe($accounts);
});

it('setPhones sets array value and returns $this', function () {
    $phones = ['+361234567', '+367654321'];
    $result = $this->model->setPhones($phones);
    expect($result)->toBe($this->model);
    expect($this->model->getPhones())->toBe($phones);
});

it('setEmails sets array value and returns $this', function () {
    $emails = ['a@b.com', 'c@d.com'];
    $result = $this->model->setEmails($emails);
    expect($result)->toBe($this->model);
    expect($this->model->getEmails())->toBe($emails);
});

it('setWebpages sets array value and returns $this', function () {
    $pages = ['https://example.com', 'https://test.com'];
    $result = $this->model->setWebpages($pages);
    expect($result)->toBe($this->model);
    expect($this->model->getWebpages())->toBe($pages);
});

it('setNegativeInfo sets model array value and returns $this', function () {
    $items = [new NegativeInfo];
    $result = $this->model->setNegativeInfo($items);
    expect($result)->toBe($this->model);
    expect($this->model->getNegativeInfo())->toBe($items);
});

it('setPositiveInfo sets model array value and returns $this', function () {
    $items = [new PositiveInfo];
    $result = $this->model->setPositiveInfo($items);
    expect($result)->toBe($this->model);
    expect($this->model->getPositiveInfo())->toBe($items);
});

it('setFinancialSummaries sets model array value and returns $this', function () {
    $items = [new FinancialSummary];
    $result = $this->model->setFinancialSummaries($items);
    expect($result)->toBe($this->model);
    expect($this->model->getFinancialSummaries())->toBe($items);
});

it('setSigners sets model array value and returns $this', function () {
    $items = [new Signer];
    $result = $this->model->setSigners($items);
    expect($result)->toBe($this->model);
    expect($this->model->getSigners())->toBe($items);
});

it('setMembers sets model array value and returns $this', function () {
    $items = [new Member];
    $result = $this->model->setMembers($items);
    expect($result)->toBe($this->model);
    expect($this->model->getMembers())->toBe($items);
});

it('setAuditors sets model array value and returns $this', function () {
    $items = [new Auditor];
    $result = $this->model->setAuditors($items);
    expect($result)->toBe($this->model);
    expect($this->model->getAuditors())->toBe($items);
});

it('setSites sets Address array value and returns $this', function () {
    $items = [new Address];
    $result = $this->model->setSites($items);
    expect($result)->toBe($this->model);
    expect($this->model->getSites())->toBe($items);
});

it('setHasDeletedTaxNumber sets bool value and returns $this', function () {
    $result = $this->model->setHasDeletedTaxNumber(true);
    expect($result)->toBe($this->model);
    expect($this->model->getHasDeletedTaxNumber())->toBeTrue();
});

it('setHasActivePositiveInfo sets bool value and returns $this', function () {
    $result = $this->model->setHasActivePositiveInfo(false);
    expect($result)->toBe($this->model);
    expect($this->model->getHasActivePositiveInfo())->toBeFalse();
});

it('setHasActiveNegativeInfo sets bool value and returns $this', function () {
    $result = $this->model->setHasActiveNegativeInfo(true);
    expect($result)->toBe($this->model);
    expect($this->model->getHasActiveNegativeInfo())->toBeTrue();
});

it('setIsKoztartozasmentes sets bool value and returns $this', function () {
    $result = $this->model->setIsKoztartozasmentes(true);
    expect($result)->toBe($this->model);
    expect($this->model->getIsKoztartozasmentes())->toBeTrue();
});

it('setIsMegbizhatoAdozo sets bool value and returns $this', function () {
    $result = $this->model->setIsMegbizhatoAdozo(false);
    expect($result)->toBe($this->model);
    expect($this->model->getIsMegbizhatoAdozo())->toBeFalse();
});

it('setHasProhibitedMember sets bool value and returns $this', function () {
    $result = $this->model->setHasProhibitedMember(true);
    expect($result)->toBe($this->model);
    expect($this->model->getHasProhibitedMember())->toBeTrue();
});

it('setSignerChangeIn12Months sets bool value and returns $this', function () {
    $result = $this->model->setSignerChangeIn12Months(false);
    expect($result)->toBe($this->model);
    expect($this->model->getSignerChangeIn12Months())->toBeFalse();
});

it('setMemberChangeIn12Months sets bool value and returns $this', function () {
    $result = $this->model->setMemberChangeIn12Months(true);
    expect($result)->toBe($this->model);
    expect($this->model->getMemberChangeIn12Months())->toBeTrue();
});

it('setHeadquarterChangeIn12Months sets bool value and returns $this', function () {
    $result = $this->model->setHeadquarterChangeIn12Months(false);
    expect($result)->toBe($this->model);
    expect($this->model->getHeadquarterChangeIn12Months())->toBeFalse();
});

it('throws on null value for non-nullable string properties', function (string $property) {
    $setters = Company::setters();
    $setter = $setters[$property];
    $this->model->{$setter}(null);
})->throws(InvalidArgumentException::class)->with([
    ['regnumber'],
    ['taxnumber'],
    ['name'],
    ['long_name'],
    ['status'],
    ['funds'],
    ['main_activity_code'],
    ['main_activity_description'],
    ['industry'],
    ['type'],
    ['ksh_number'],
    ['eu_taxnumber'],
    ['link'],
]);

it('throws on null value for non-nullable int properties', function (string $property) {
    $setters = Company::setters();
    $setter = $setters[$property];
    $this->model->{$setter}(null);
})->throws(InvalidArgumentException::class)->with([
    ['employees'],
    ['last_turnover'],
    ['rating'],
    ['credit_limit'],
]);

it('throws on null value for non-nullable bool properties', function (string $property) {
    $setters = Company::setters();
    $setter = $setters[$property];
    $this->model->{$setter}(null);
})->throws(InvalidArgumentException::class)->with([
    ['has_deleted_tax_number'],
    ['has_active_positive_info'],
    ['has_active_negative_info'],
    ['is_koztartozasmentes'],
    ['is_megbizhato_adozo'],
    ['has_prohibited_member'],
    ['signer_change_in12_months'],
    ['member_change_in12_months'],
    ['headquarter_change_in12_months'],
]);

it('throws on null value for non-nullable array properties', function (string $property) {
    $setters = Company::setters();
    $setter = $setters[$property];
    $this->model->{$setter}(null);
})->throws(InvalidArgumentException::class)->with([
    ['bank_accounts'],
    ['phones'],
    ['emails'],
    ['webpages'],
    ['negative_info'],
    ['positive_info'],
    ['financial_summaries'],
    ['signers'],
    ['members'],
    ['auditors'],
    ['sites'],
]);

it('throws on null value for headquarter and foundation', function (string $property) {
    $setters = Company::setters();
    $setter = $setters[$property];
    $this->model->{$setter}(null);
})->throws(InvalidArgumentException::class)->with([
    ['headquarter'],
    ['foundation'],
]);

it('getStatusAllowableValues returns correct enum values', function () {
    $values = $this->model->getStatusAllowableValues();
    expect($values)->toBe(['Működő', 'Eljárás folyamatban', 'Megszűnt']);
});

it('constructor with null initializes all properties to null', function () {
    $model = new Company;
    expect($model->getRegnumber())->toBeNull();
    expect($model->getName())->toBeNull();
    expect($model->getEmployees())->toBeNull();
    expect($model->getHasDeletedTaxNumber())->toBeNull();
    expect($model->getHeadquarter())->toBeNull();
    expect($model->getFoundation())->toBeNull();
});

it('constructor with data sets provided properties', function () {
    $address = new Address;
    $model = new Company([
        'regnumber' => '01-09-123456',
        'name' => 'Test',
        'employees' => 10,
        'has_deleted_tax_number' => false,
        'headquarter' => $address,
    ]);
    expect($model->getRegnumber())->toBe('01-09-123456');
    expect($model->getName())->toBe('Test');
    expect($model->getEmployees())->toBe(10);
    expect($model->getHasDeletedTaxNumber())->toBeFalse();
    expect($model->getHeadquarter())->toBe($address);
});

it('constructor with partial data leaves others as null', function () {
    $model = new Company(['regnumber' => '01-09-123456']);
    expect($model->getRegnumber())->toBe('01-09-123456');
    expect($model->getName())->toBeNull();
});

it('implements ArrayAccess: offsetExists', function () {
    $this->model->setRegnumber('01-09-123456');
    expect($this->model->offsetExists('regnumber'))->toBeTrue();
    expect($this->model->offsetExists('nonexistent'))->toBeFalse();
});

it('implements ArrayAccess: offsetGet', function () {
    $this->model->setRegnumber('01-09-123456');
    expect($this->model->offsetGet('regnumber'))->toBe('01-09-123456');
    expect($this->model->offsetGet('nonexistent'))->toBeNull();
});

it('implements ArrayAccess: offsetSet with key', function () {
    $this->model->offsetSet('regnumber', 'new-value');
    expect($this->model->offsetGet('regnumber'))->toBe('new-value');
});

it('implements ArrayAccess: offsetSet without key', function () {
    $this->model->offsetSet(null, 'appended');
    expect($this->model->offsetGet(0))->toBe('appended');
});

it('implements ArrayAccess: offsetUnset', function () {
    $this->model->setRegnumber('01-09-123456');
    $this->model->offsetUnset('regnumber');
    expect($this->model->offsetExists('regnumber'))->toBeFalse();
});

it('jsonSerialize returns array', function () {
    $this->model->setRegnumber('01-09-123456');
    $serialized = $this->model->jsonSerialize();
    expect($serialized)->toBeObject();
});

it('__toString returns JSON string', function () {
    $this->model->setRegnumber('01-09-123456');
    $str = $this->model->__toString();
    expect($str)->toBeString();
    expect($str)->toBeString()->not->toBeEmpty();
});

it('toHeaderValue returns compact JSON string', function () {
    $this->model->setRegnumber('01-09-123456');
    $value = $this->model->toHeaderValue();
    expect($value)->toBeString();
    expect(json_decode($value, true))->toBeArray();
});

it('isNullable returns false for all properties', function (string $property) {
    expect(Company::isNullable($property))->toBeFalse();
})->with([
    ['regnumber'],
    ['name'],
    ['status'],
    ['employees'],
    ['has_deleted_tax_number'],
    ['headquarter'],
    ['foundation'],
    ['signers'],
    ['negative_info'],
]);

it('isNullable returns false for unknown property', function () {
    expect(Company::isNullable('unknown'))->toBeFalse();
});

it('isNullableSetToNull initially returns false', function () {
    expect($this->model->isNullableSetToNull('regnumber'))->toBeFalse();
    expect($this->model->isNullableSetToNull('name'))->toBeFalse();
});

it('listInvalidProperties empty for valid empty model', function () {
    expect($this->model->listInvalidProperties())->toBeEmpty();
});

it('listInvalidProperties catches invalid status enum', function () {
    $model = new Company(['status' => 'InvalidValue']);
    $invalid = $model->listInvalidProperties();
    expect($invalid)->not->toBeEmpty();
    expect($invalid[0])->toContain("invalid value 'InvalidValue' for 'status'");
});

it('listInvalidProperties allows null status', function () {
    expect($this->model->listInvalidProperties())->toBeEmpty();
});

it('valid returns true for empty model', function () {
    expect($this->model->valid())->toBeTrue();
});

it('valid returns false with invalid status', function () {
    $model = new Company(['status' => 'InvalidValue']);
    expect($model->valid())->toBeFalse();
});

it('valid returns true with valid status', function () {
    $this->model->setStatus('Működő');
    expect($this->model->valid())->toBeTrue();
});
