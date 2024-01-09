<?php

namespace App\Http\Errors;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ServerError extends HttpException
{
    public function __construct(string $message = 'Ops... Um erro inesperado ocorreu. Tente novamente mais tarde!')
    {
        parent::__construct(404, $message);
    }
}
