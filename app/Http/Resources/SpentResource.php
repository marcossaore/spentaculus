<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $request = parent::toArray($request);

        return [
            'id' => $this->id,
            'description'   => $this->description,
            'value'  => $this->value,
            'spentAt'  => $request[ 'spent_at'],
            'createdAt'  => $request['created_at'],
            'updatedAt'  => $request['updated_at']
        ];
    }
}
