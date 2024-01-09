<?php

namespace App\Http\Dto;

use Exception;

trait DtoConstraints
{
    function __get($property) {
        if ($this->$property === null) {
            throw new Exception("$property is required");
        }
        return $this->$property;
    }
}
