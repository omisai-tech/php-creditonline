<?php

use Omisai\CreditOnline\HeaderSelector;

// ---- selectHeaders ----

it('selectHeaders returns Accept header when accept array is provided', function () {
    $selector = new HeaderSelector();

    $headers = $selector->selectHeaders(['application/json'], '', false);

    expect($headers)->toHaveKey('Accept', 'application/json')
        ->toHaveKey('Content-Type', 'application/json');
});

it('selectHeaders uses content type application/json when empty string is given', function () {
    $selector = new HeaderSelector();

    $headers = $selector->selectHeaders(['application/json'], '', false);

    expect($headers['Content-Type'])->toBe('application/json');
});

it('selectHeaders preserves custom content type', function () {
    $selector = new HeaderSelector();

    $headers = $selector->selectHeaders(['application/json'], 'text/xml', false);

    expect($headers['Content-Type'])->toBe('text/xml');
});

it('selectHeaders for multipart does not include Content-Type', function () {
    $selector = new HeaderSelector();

    $headers = $selector->selectHeaders(['application/json'], '', true);

    expect($headers)->toHaveKey('Accept', 'application/json');
    expect($headers)->not->toHaveKey('Content-Type');
});

it('selectHeaders returns no Accept header when accept array is empty', function () {
    $selector = new HeaderSelector();

    $headers = $selector->selectHeaders([], '', false);

    expect($headers)->not->toHaveKey('Accept');
    expect($headers)->toHaveKey('Content-Type', 'application/json');
});

it('selectHeaders returns no Accept header when accept has only empty strings', function () {
    $selector = new HeaderSelector();

    $headers = $selector->selectHeaders(['', ''], '', false);

    expect($headers)->not->toHaveKey('Accept');
});

// ---- selectHeaders: Accept header selection with single value ----

it('selectHeaders uses the single accept value directly', function () {
    $selector = new HeaderSelector();

    $headers = $selector->selectHeaders(['text/html'], '', false);

    expect($headers['Accept'])->toBe('text/html');
});

// ---- isJsonMime ----

it('isJsonMime returns true for application/json', function () {
    $selector = new HeaderSelector();

    expect($selector->isJsonMime('application/json'))->toBeTrue();
});

it('isJsonMime returns true for application/vnd.api+json', function () {
    $selector = new HeaderSelector();

    expect($selector->isJsonMime('application/vnd.api+json'))->toBeTrue();
});

it('isJsonMime returns true for json mime with parameters', function () {
    $selector = new HeaderSelector();

    expect($selector->isJsonMime('application/json; charset=utf-8'))->toBeTrue();
});

it('isJsonMime returns false for text/html', function () {
    $selector = new HeaderSelector();

    expect($selector->isJsonMime('text/html'))->toBeFalse();
});

it('isJsonMime returns false for text/plain', function () {
    $selector = new HeaderSelector();

    expect($selector->isJsonMime('text/plain'))->toBeFalse();
});

it('isJsonMime returns false for application/xml', function () {
    $selector = new HeaderSelector();

    expect($selector->isJsonMime('application/xml'))->toBeFalse();
});

// ---- getNextWeight ----

it('getNextWeight returns 900 when starting from 1000 with less than 28 headers', function () {
    $selector = new HeaderSelector();

    expect($selector->getNextWeight(1000, false))->toBe(900);
});

it('getNextWeight returns 800 when starting from 900 with less than 28 headers', function () {
    $selector = new HeaderSelector();

    expect($selector->getNextWeight(900, false))->toBe(800);
});

it('getNextWeight returns 100 when starting from 200 with less than 28 headers', function () {
    $selector = new HeaderSelector();

    expect($selector->getNextWeight(200, false))->toBe(100);
});

it('getNextWeight returns 90 when starting from 100 with less than 28 headers', function () {
    $selector = new HeaderSelector();

    expect($selector->getNextWeight(100, false))->toBe(90);
});

it('getNextWeight returns 10 when starting from 20 with less than 28 headers', function () {
    $selector = new HeaderSelector();

    expect($selector->getNextWeight(20, false))->toBe(10);
});

it('getNextWeight returns 1 when starting from 1', function () {
    $selector = new HeaderSelector();

    expect($selector->getNextWeight(1, false))->toBe(1);
});

it('getNextWeight returns 1 when starting from 0', function () {
    $selector = new HeaderSelector();

    expect($selector->getNextWeight(0, false))->toBe(1);
});

it('getNextWeight decrements by 1 when more than 28 headers', function () {
    $selector = new HeaderSelector();

    expect($selector->getNextWeight(1000, true))->toBe(999);
    expect($selector->getNextWeight(500, true))->toBe(499);
});

// ---- Weight series validation ----

it('getNextWeight generates the correct series for less than 28 headers', function () {
    $selector = new HeaderSelector();
    $weights = [];
    $current = 1000;

    while ($current > 1) {
        $weights[] = $current;
        $current = $selector->getNextWeight($current, false);
    }
    $weights[] = 1;

    $expected = [1000, 900, 800, 700, 600, 500, 400, 300, 200, 100, 90, 80, 70, 60, 50, 40, 30, 20, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1];

    expect($weights)->toBe($expected);
});

// ---- selectHeaders with multiple accept values ----

it('selectHeaders combines multiple accept headers with quality weights', function () {
    $selector = new HeaderSelector();

    $headers = $selector->selectHeaders(['application/json', 'text/html'], '', false);

    expect($headers['Accept'])->toContain('application/json')
        ->toContain('text/html');
});

it('selectHeaders with only non-json accept headers returns comma-separated list', function () {
    $selector = new HeaderSelector();

    $headers = $selector->selectHeaders(['text/html', 'text/plain'], '', false);

    expect($headers['Accept'])->toBe('text/html,text/plain');
});

it('selectHeaders handles json-like mime types with quality weighting', function () {
    $selector = new HeaderSelector();

    $headers = $selector->selectHeaders(['application/json', 'application/vnd.api+json', 'text/html'], '', false);

    expect($headers['Accept'])->toContain('application/json');
    expect($headers['Accept'])->toContain('application/vnd.api+json');
    expect($headers['Accept'])->toContain('text/html');
});
