<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

final class OpenMeteoException extends HttpException
{
    public function __construct(
        string $message = 'An error occurred with OpenMeteo API',
        ?\Throwable $previous = null,
    ) {
        parent::__construct(502, $message, $previous);
    }
}
