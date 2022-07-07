<?php

namespace App\Http\Resources\v1\combos;

use Illuminate\Http\Resources\Json\JsonResource;

class ComboResource extends JsonResource
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
            'type' => 'combos',
            'attributes' => [
                'name' => $this->name,
                'desc_min' => $this->desc_min,
                'desc_may' => $this->desc_may,
                'precio_min' => $this->precio_min,
                'precio_may' => $this->precio_may,
            ],
            'relationships' => [
                'comboitems' => ComboitemResource::collection($this->comboitems),
            ]
        ];
    }
}
