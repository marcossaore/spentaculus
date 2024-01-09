<?php

namespace App\Http\Dto\Spent;

use App\Http\Dto\FromArrayToDto;
use App\Http\Dto\FromDtoToEntityArray;

class SpentUpdateDto implements FromArrayToDto, FromDtoToEntityArray
{
    public function toDto(array $data)
    {
        if (isset($data['description'])) {
            $this->{'description'} = $data['description'];
        }

        if (isset($data['value'])) {
            $this->{'value'} = $data['value'];
        }

        if (isset($data['spentAt'])) {
            $this->{'spentAt'} = $data['spentAt'];
        }

        return $this;
    }

    public function toEntityArray()
    {
        $data = [];

        if (isset($this->{'description'})) {
            $data['description'] = $this->{'description'};
        }

        if (isset($this->{'value'})) {
            $data['value'] = $this->{'value'};
        }

        if (isset($this->{'spentAt'})) {
            $data['spent_at'] = $this->{'spentAt'};
        }

        return $data;
    }
}
