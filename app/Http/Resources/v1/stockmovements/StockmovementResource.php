<?php

namespace App\Http\Resources\v1\stockmovements;

use Illuminate\Http\Resources\Json\JsonResource;

class StockmovementResource extends JsonResource
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
            'type' => 'stockmovements',
            'attributes' => [
                'created_at' => $this->created_at,
                'estado' => $this->estado,
                'tipo' => $this->tipo,
            ],
            'relationships' => [
                'user' => [
                    'id' => $this->user->id,
                    'attributes' => [
                        'name' => $this->user->name
                    ] 
                ],
                'sucursal' => $this->sucursal ? [
                    'id' => $this->sucursal->id,
                    'attributes' => [
                        'name' => $this->sucursal->name
                    ],
                ] : null, 
                'stockmovementitems' => StockmovementitemResource::collection($this->stockmovementitems),              
            ]
        ];
    }
}
