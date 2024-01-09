<?php

namespace App\Services;

use App\Contracts\Services\User\CreateUserServiceContract;
use App\Http\Dto\UserSignUpDto;
use App\Models\User;

class UserService implements CreateUserServiceContract
{
    public function __construct(private readonly User $userModel)
    {
    }

    /**
     * @param UserSignUpDto $data
     */
    public function create($data): User
    {
        return $this->userModel->create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => $data->password,
        ]);
    }
}
