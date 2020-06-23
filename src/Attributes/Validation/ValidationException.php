<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties\Attributes\Validation;

use InvalidArgumentException;

class ValidationException extends InvalidArgumentException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}