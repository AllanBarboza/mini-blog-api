<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class UserNotFoundException extends HttpException
{
    public function __construct(string $message = 'User not found.')
    {
        parent::__construct(404, $message);
    }
}
