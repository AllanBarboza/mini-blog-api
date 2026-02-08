<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ConflictException extends HttpException
{
    public function __construct(string $message = 'Conflict')
    {
        parent::__construct(409, $message);
    }
}
