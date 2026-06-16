<?php

declare(strict_types=1);

namespace Seo\IndexNow;

use Stringable;

final readonly class IndexNowKey implements Stringable
{
    private const string PATTERN = '/^[a-zA-Z0-9\\-]{8,128}$/';

    private function __construct(public readonly string $value)
    {
        if (preg_match(self::PATTERN, $value) !== 1) {
            throw InvalidIndexNowKeyException::invalidFormat($value);
        }
    }

    public static function make(string $value): self
    {
        return new self($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
