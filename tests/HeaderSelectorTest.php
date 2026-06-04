<?php

use Omisai\CreditOnline\HeaderSelector;

// ---------------------------------------------------------------------------
// Fixtures
// ---------------------------------------------------------------------------

beforeEach(function () {
    $this->selector = new HeaderSelector;
});

// ---------------------------------------------------------------------------
// selectHeaders — single accept value
// ---------------------------------------------------------------------------

it('selectHeaders returns Accept and Content-Type for single accept', function () {
    $headers = $this->selector->selectHeaders(['application/json'], 'application/xml', false);

    expect($headers)->toBeArray()
        ->toHaveKeys(['Accept', 'Content-Type']);
    expect($headers['Accept'])->toBe('application/json');
    expect($headers['Content-Type'])->toBe('application/xml');
});

it('selectHeaders returns headers with single accept and empty content type defaults to json', function () {
    $headers = $this->selector->selectHeaders(['text/html'], '', false);

    expect($headers)->toBeArray()
        ->toHaveKeys(['Accept', 'Content-Type']);
    expect($headers['Accept'])->toBe('text/html');
    expect($headers['Content-Type'])->toBe('application/json');
});

// ---------------------------------------------------------------------------
// selectHeaders — empty accept
// ---------------------------------------------------------------------------

it('selectHeaders with empty accept returns no Accept header', function () {
    $headers = $this->selector->selectHeaders([], 'application/json', false);

    expect($headers)->not->toHaveKey('Accept');
    expect($headers)->toHaveKeys(['Content-Type']);
});

it('selectHeaders with array of empty strings returns no Accept header', function () {
    $headers = $this->selector->selectHeaders(['', ''], 'application/json', false);

    expect($headers)->not->toHaveKey('Accept');
    expect($headers)->toHaveKeys(['Content-Type']);
});

// ---------------------------------------------------------------------------
// selectHeaders — multiple accept values (comma-separated)
// ---------------------------------------------------------------------------

it('selectHeaders joins multiple non-json accepts with commas', function () {
    $headers = $this->selector->selectHeaders(['text/html', 'text/plain'], 'application/json', false);

    expect($headers['Accept'])->toBe('text/html,text/plain');
});

it('selectHeaders uses weighted Accept for mixed json and non-json', function () {
    $headers = $this->selector->selectHeaders(
        ['application/json', 'text/html', 'text/plain'],
        'application/json',
        false
    );

    expect($headers['Accept'])->toContain('application/json');
    expect($headers['Accept'])->toContain('text/html');
    expect($headers['Accept'])->toContain('text/plain');
});

// ---------------------------------------------------------------------------
// selectHeaders — multipart
// ---------------------------------------------------------------------------

it('selectHeaders with multipart omits Content-Type', function () {
    $headers = $this->selector->selectHeaders(['application/json'], 'multipart/form-data', true);

    expect($headers)->toHaveKeys(['Accept']);
    expect($headers)->not->toHaveKey('Content-Type');
});

it('selectHeaders with multipart and single accept', function () {
    $headers = $this->selector->selectHeaders(['application/json'], '', true);

    expect($headers)->toBeArray()
        ->toHaveKeys(['Accept']);
    expect($headers['Accept'])->toBe('application/json');
    expect($headers)->not->toHaveKey('Content-Type');
});

// ---------------------------------------------------------------------------
// isJsonMime
// ---------------------------------------------------------------------------

it('isJsonMime returns true for application/json', function () {
    expect($this->selector->isJsonMime('application/json'))->toBeTrue();
});

it('isJsonMime returns true for application/vnd.api+json', function () {
    expect($this->selector->isJsonMime('application/vnd.api+json'))->toBeTrue();
});

it('isJsonMime returns true for application/problem+json', function () {
    expect($this->selector->isJsonMime('application/problem+json'))->toBeTrue();
});

it('isJsonMime returns true for application/schema+json', function () {
    expect($this->selector->isJsonMime('application/schema+json'))->toBeTrue();
});

it('isJsonMime returns true for application/hal+json', function () {
    expect($this->selector->isJsonMime('application/hal+json'))->toBeTrue();
});

it('isJsonMime returns true for application/json with charset', function () {
    expect($this->selector->isJsonMime('application/json; charset=utf-8'))->toBeTrue();
});

it('isJsonMime returns true for application/vnd.api+json with charset', function () {
    expect($this->selector->isJsonMime('application/vnd.api+json; charset=utf-8'))->toBeTrue();
});

it('isJsonMime returns false for text/html', function () {
    expect($this->selector->isJsonMime('text/html'))->toBeFalse();
});

it('isJsonMime returns false for text/plain', function () {
    expect($this->selector->isJsonMime('text/plain'))->toBeFalse();
});

it('isJsonMime returns false for application/xml', function () {
    expect($this->selector->isJsonMime('application/xml'))->toBeFalse();
});

it('isJsonMime returns false for application/octet-stream', function () {
    expect($this->selector->isJsonMime('application/octet-stream'))->toBeFalse();
});

it('isJsonMime returns false for text/csv', function () {
    expect($this->selector->isJsonMime('text/csv'))->toBeFalse();
});

it('isJsonMime returns false for html that contains json substring', function () {
    expect($this->selector->isJsonMime('text/html+json'))->toBeFalse();
});

it('isJsonMime returns false for empty string', function () {
    expect($this->selector->isJsonMime(''))->toBeFalse();
});

// ---------------------------------------------------------------------------
// getNextWeight — less than 28 headers (logarithmic decrement)
// ---------------------------------------------------------------------------

it('getNextWeight sequence: 1000 -> 900 (less than 28 headers)', function () {
    expect($this->selector->getNextWeight(1000, false))->toBe(900);
});

it('getNextWeight sequence: 900 -> 800', function () {
    expect($this->selector->getNextWeight(900, false))->toBe(800);
});

it('getNextWeight sequence: 800 -> 700', function () {
    expect($this->selector->getNextWeight(800, false))->toBe(700);
});

it('getNextWeight sequence: 700 -> 600', function () {
    expect($this->selector->getNextWeight(700, false))->toBe(600);
});

it('getNextWeight sequence: 100 -> 90', function () {
    expect($this->selector->getNextWeight(100, false))->toBe(90);
});

it('getNextWeight sequence: 90 -> 80', function () {
    expect($this->selector->getNextWeight(90, false))->toBe(80);
});

it('getNextWeight sequence: 10 -> 9', function () {
    expect($this->selector->getNextWeight(10, false))->toBe(9);
});

it('getNextWeight sequence: 9 -> 8', function () {
    expect($this->selector->getNextWeight(9, false))->toBe(8);
});

it('getNextWeight sequence: 2 -> 1', function () {
    expect($this->selector->getNextWeight(2, false))->toBe(1);
});

// ---------------------------------------------------------------------------
// getNextWeight — more than 28 headers (linear decrement)
// ---------------------------------------------------------------------------

it('getNextWeight with more than 28 headers: 1000 -> 999', function () {
    expect($this->selector->getNextWeight(1000, true))->toBe(999);
});

it('getNextWeight with more than 28 headers: 999 -> 998', function () {
    expect($this->selector->getNextWeight(999, true))->toBe(998);
});

it('getNextWeight with more than 28 headers: 500 -> 499', function () {
    expect($this->selector->getNextWeight(500, true))->toBe(499);
});

// ---------------------------------------------------------------------------
// getNextWeight — boundary values
// ---------------------------------------------------------------------------

it('getNextWeight returns 1 when current weight is 1 (less than 28)', function () {
    expect($this->selector->getNextWeight(1, false))->toBe(1);
});

it('getNextWeight returns 1 when current weight is 1 (more than 28)', function () {
    expect($this->selector->getNextWeight(1, true))->toBe(1);
});

it('getNextWeight returns 1 when current weight is 0 (less than 28)', function () {
    expect($this->selector->getNextWeight(0, false))->toBe(1);
});

it('getNextWeight returns 1 when current weight is 0 (more than 28)', function () {
    expect($this->selector->getNextWeight(0, true))->toBe(1);
});
