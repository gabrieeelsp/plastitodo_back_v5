<?php

namespace App\Http\Resources\v1\saleproducts;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleproductVentaResource extends JsonResource
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
            'type' => 'saleproducts',
            'attributes' => [
                'name' => $this->name,
                'porc_min' => $this->porc_min,
                'porc_may' => $this->porc_may,
                'relacion_venta_stock' => $this->relacion_venta_stock,
            ],
            'relationships' => [
                'stockproduct' => [
                    'data' => [
                        'id' => $this->stockproduct_id,
                        'type' => 'stockproducts',
                        'attributes' => [
                            'name' => $this->stockproduct->name,
                            'costo' => $this->stockproduct->costo,
                            'stock' => $this->stockproduct->getStockSucursal($request->get('sucursal')),
                            'is_stock_unitario_kilo' => $this->stockproduct->is_stock_unitario_kilo,
                            'stock_aproximado_unidad' => $this->stockproduct->stock_aproximado_unidad
                        ]
                    ]
                ]
                
            ]
        ];
    }
}
