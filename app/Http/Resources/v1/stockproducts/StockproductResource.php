<?php

namespace App\Http\Resources\v1\stockproducts;

use Illuminate\Http\Resources\Json\JsonResource;

class StockproductResource extends JsonResource
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
            'type' => 'stockproducts',
            'attributes' => [
                'name' => $this->name,
                'costo' => $this->costo,
                'is_stock_unitario_variable' => $this->is_stock_unitario_variable,
                'stock_aproximado_unidad' => $this->stock_aproximado_unidad,

                'image'     => $this->image ? asset($this->image) : null,
            ],
            'relationships' => [
                'ivaaliquot' => [
                    'id' => $this->ivaaliquot_id,
                    'attributes' => [
                        'name' => $this->ivaaliquot->name,
                        'valor' => $this->ivaaliquot->valor,
                        'id_afip' => $this->ivaaliquot->id_afip
                    ]
                ],
                'stockproductgroup' => $this->stockproductgroup ? [
                    'id' => $this->stockproductgroup->id,
                    'attributes' => [
                        'name' => $this->stockproductgroup->name,
                    ]
                ] : null,
                'saleproducts' => SaleproductResource::collection($this->saleproducts),
                'purchaseproducts' => PurchaseproductResource::collection($this->purchaseproducts),
            ]
        ];
    }
}
