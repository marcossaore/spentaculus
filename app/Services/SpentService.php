<?php

namespace App\Services;

use App\Http\Dto\Spent\SpentCreateDto;
use App\Models\Spent;
use App\Models\User;

class SpentService
{
    public function __construct(
        private readonly Spent $spentModel
    )
    {}

    public function create(User $user, SpentCreateDto $data): Spent
    {
        $spent = $this->spentModel->newInstance([
            'description' => $data->description,
            'value' => $data->value
        ]);


        return $user->spents()->save($spent)->refresh();
    }
}
