<?php

namespace App\Http\Resources\v1\combos;

use Illuminate\Http\Resources\Json\JsonResource;

class ComboitemsaleproductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
