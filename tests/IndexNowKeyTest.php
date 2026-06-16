<?php

declare(strict_types=1);

use Seo\IndexNow\IndexNowKey;
use Seo\IndexNow\InvalidIndexNowKeyException;

it('accepts a valid 8-character key', function (): void {
    $key = IndexNowKey::make('abcd1234');

    expect($key->value)->toBe('abcd1234');
});

it('accepts a valid key with dashes', function (): void {
    $key = IndexNowKey::make('valid-key-1234');

    expect($key->value)->toBe('valid-key-1234');
});

it('accepts a key at maximum length of 128 characters', function (): void {
    $value = str_repeat('a', 128);
    $key = IndexNowKey::make($value);

    expect($key->value)->toBe($value);
});

it('converts to string via __toString', function (): void {
    $key = IndexNowKey::make('abcd1234');

    expect((string) $key)->toBe('abcd1234');
});

it('throws for an empty key', function (): void {
    IndexNowKey::make('');
})->throws(InvalidIndexNowKeyException::class);

it('throws for a key shorter than 8 characters', function (): void {
    IndexNowKey::make('short');
})->throws(InvalidIndexNowKeyException::class);

it('throws for a key longer than 128 characters', function (): void {
    IndexNowKey::make(str_repeat('a', 129));
})->throws(InvalidIndexNowKeyException::class);

it('throws for a key with underscores', function (): void {
    IndexNowKey::make('invalid_key_1234');
})->throws(InvalidIndexNowKeyException::class);

it('throws for a key with spaces', function (): void {
    IndexNowKey::make('invalid key1234');
})->throws(InvalidIndexNowKeyException::class);

it('throws for a key with special characters', function (): void {
    IndexNowKey::make('invalid!key@1234');
})->throws(InvalidIndexNowKeyException::class);

it('exception message contains the invalid key', function (): void {
    expect(fn () => IndexNowKey::make('bad'))
        ->toThrow(InvalidIndexNowKeyException::class, '"bad"')
    ;
});
