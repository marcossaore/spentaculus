<?php

namespace App\Http\Dto;

interface FromArrayToDto
{
    function toDto(array $data);
}
