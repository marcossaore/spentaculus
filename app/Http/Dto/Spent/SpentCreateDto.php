<?php

namespace App\Http\Dto\Spent;

use App\Http\Dto\DtoConstraints;
use App\Http\Dto\FromArrayToDto;
use App\Http\Dto\FromDtoToEntity;
use App\Models\Spent;
use Exception;

class SpentCreateDto implements FromArrayToDto, FromDtoToEntity
{
    use DtoConstraints;

    public readonly string $description;
    public readonly string $value;
    public readonly string $spentAt;

    public function toDto(array $data)
    {
        if (!$data['description'] || !$data['value']) {
            throw new Exception('Description and value are required.');
        }

        $this->description = $data['description'];
        $this->value = $data['value'];

        if (isset($data['spentAt'])) {
            $this->spentAt = $data['spentAt'];
        }

        return $this;
    }

    public function toEntity()
    {
        $spent = new Spent([
            'description' => $this->description,
            'value' => $this->value
        ]);

        if (isset($this->spentAt)) {
            $spent->spent_at = $this->spentAt;
        }

        return $spent;
    }
}
