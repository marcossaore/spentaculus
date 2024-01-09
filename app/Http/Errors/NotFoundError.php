<?php

namespace App\Http\Errors;

use Symfony\Component\HttpKernel\Exception\HttpException;

class NotFoundError extends HttpException
{
    public function __construct(string $message)
    {
        parent::__construct(404, $message);
    }
}
