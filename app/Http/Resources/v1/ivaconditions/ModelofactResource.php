<?php

namespace App\Http\Resources\v1\ivaconditions;

use Illuminate\Http\Resources\Json\JsonResource;

class ModelofactResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name
            ]
        ];
    }
}
