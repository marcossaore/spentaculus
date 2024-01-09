<?php

namespace App\Http\Errors;

use Symfony\Component\HttpKernel\Exception\HttpException;

class UnauthorizedError extends HttpException
{
    public function __construct(string $message = 'Usuário ou senha inválidos!')
    {
        parent::__construct(401, $message);
    }
}
