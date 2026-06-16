<?php

declare(strict_types=1);

namespace Seo\IndexNow;

use InvalidArgumentException;

final class InvalidIndexNowKeyException extends InvalidArgumentException implements IndexNowException
{
    public static function invalidFormat(string $key): self
    {
        return new self(sprintf(
            'IndexNow key "%s" is invalid. Keys must be 8-128 characters long and contain only [a-zA-Z0-9-].',
            $key,
        ));
    }
}
