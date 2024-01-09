<?php
namespace App\Contracts\Services\User;

/**
 * @template T
 */
interface CreateUserServiceContract
{
    /**
     * @param object $data
     * @return T
     */
    public function create(object $data);
}
