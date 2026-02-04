<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class PreconditionFailedException extends HttpException
{
    public function __construct(string $message = 'Precondion failed')
    {
        parent::__construct(412, $message);
    }
}
