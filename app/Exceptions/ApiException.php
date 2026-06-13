<?php

namespace App\Exceptions;

use Exception;

/**
 * Generic business-rule exception carrying an HTTP status code.
 * Caught by the global exception handler and rendered using the
 * standard { success: false, message, errors } envelope.
 */
class ApiException extends Exception
{
    public function __construct(string $message, protected int $status = 422)
    {
        parent::__construct($message);
    }

    public function status(): int
    {
        return $this->status;
    }
}
