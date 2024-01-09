<?php

namespace App\Http\Dto\Auth;

use App\Http\Dto\DtoConstraints;
use App\Http\Dto\FromArrayToDto;
use Exception;

class UserSignUpDto implements FromArrayToDto
{
    use DtoConstraints;

    public readonly string $name;
    public readonly string $email;
    public readonly string $password;

    public function toDto(array $data)
    {
        if (!$data['name'] || !$data['email'] || !$data['password']) {
            throw new Exception('Name, email, and password are required.');
        }

        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->password = $data['password'];

        return $this;
    }
}
