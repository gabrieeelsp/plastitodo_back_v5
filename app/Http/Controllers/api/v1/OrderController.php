<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Orderitem;
use App\Models\Ordercomboitem;
use App\Models\Ordercombosaleproduct;
use App\Models\Combo;
use App\Models\Stocksucursal;
use App\Models\Sale;
use App\Models\Saleitem;
use App\Models\Salecomboitem;
use App\Models\Salecombosaleproduct;

use App\Models\User;


use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Http\Resources\v1\orders\OrderResource;
use App\Http\Resources\v1\orders\orderlist\OrderListResource;
use App\Http\Resources\v1\orders\orderchecksale\OrderCheckSaleResource;

use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = 5;
        if($request->has('limit')){
            $limit = $request->get('limit');
        }

        $atr = [];

        if ( $request->has('client_id')){
            array_push($atr, ['client_id', '=', $request->get('client_id')] );
        }

        if ( $request->has('state') ) {
            array_push($atr, ['state', '=', $request->get('state')] );
        }

        if ( $request->has('sucursal_id') ) {
            array_push($atr, ['sucursal_id', '=', $request->get('sucursal_id')] );
        }

        $date_from = null;
        $date_to = null;
        if ( $request->has('date_from') ) {
            $date_from = $request->get('date_from');
            if ( $request->has('date_to' )) {
                $date_to = $request->get('date_to');
            }else {
                $date_to = $request->get('date_from');
            }
        }

        // date_from----
        if ( $date_from ){

            $orders = Order::orderBy('id', 'DESC')
                ->where($atr)
                ->whereBetween('created_at', [$date_from, $date_to . ' 23:59:59'])
                ->paginate($limit);
            return OrderResource::collection($orders);
        }

        // sin date_ftom-------
        $orders = Order::orderBy('id', 'DESC')
            ->where($atr)
            ->where($atr)
            ->paginate($limit);
        return OrderResource::collection($orders);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {
        $client = User::findOrFail($request->get('client_id'));

        $order = Order::create();
        $order->client()->associate($client->id);
        $order->user()->associate(auth()->user()->id);
        $order->state = "EDITANDO";
        $order->save();

        return new OrderResource($order);
    }
    public function storeback(Request $request)
    {
        $is_finalizar = $request->get('is_finalizar');
        try {
            DB::beginTransaction();
            $order = Order::create();

            $order->user()->associate(auth()->user());

            if ( $request->has('sucursal_id')){
                $order->sucursal()->associate($request->get('sucursal_id'));
            }

            if($request->has('client_id')){
                $order->client()->associate($request->get('client_id'));
            }

            if($request->has('fecha_entrega_acordada')){
                $order->fecha_entrega_acordada = Carbon::createFromFormat('d-m-Y', $request->get('fecha_entrega_acordada'))->format('Y-m-d');
            }

            if($request->has('deliveryshift_id')){
                $order->deliveryshift()->associate($request->get('deliveryshift_id'));
            }

            if($request->has('ivacondition_id')){
                $order->ivacondition()->associate($request->get('ivacondition_id'));
            }

            if ( $request->has('is_delivery') && boolval($request->get('is_delivery'))) {
                $order->is_delivery = true;
            }else {
                $order->is_delivery = false;
            }

            $items = $request->get('items');
            foreach($items as $item){
                $orderItem = new Orderitem;

                $orderItem->order()->associate($order);
                $orderItem->saleproduct()->associate($item['saleproduct_id']);
                $orderItem->precio = $item['precio'];
                $orderItem->cantidad = $item['cantidad'];

                if($orderItem->saleproduct->stockproduct->is_stock_unitario_variable){
                    if ( $item['cantidad_total']) {
                        $orderItem->cantidad_total = $item['cantidad_total'];
                    }else {
                        $orderItem->cantidad_total = 0;
                    }
                    
                }

                $orderItem->save();
            }

            $comboitems = $request->get('comboitems');
            foreach($comboitems as $comboitem){
                $combo = Combo::find($comboitem['combo_id']);

                $ordercomboitem = new Ordercomboitem;
                $ordercomboitem->order_id = $order->id;
                $ordercomboitem->precio = $comboitem['precio'];
                $ordercomboitem->combo_id = $comboitem['combo_id'];
                $ordercomboitem->cantidad = $comboitem['cantidad'];

                $ordercomboitem->save();
                
                
                foreach($comboitem['comboitems'] as $combo_item_order) {

                    foreach($combo_item_order['saleproducts'] as $saleproduct_order){

                        $ordercombosaleproduct = new Ordercombosaleproduct;
                        $ordercombosaleproduct->cantidad = $saleproduct_order['cantidad'];
                        $ordercombosaleproduct->saleproduct()->associate($saleproduct_order['saleproduct_id']);
                        $ordercombosaleproduct->ordercomboitem()->associate($ordercomboitem->id);

                        $ordercombosaleproduct->save();
                    }
                    
                }



                $ordercomboitem->save();
            }

            $order->state = "EDITANDO";
            if ( $is_finalizar ) {
                $order->state = 'FINALIZADO';
            }
            $order->save();
            usleep(500000);

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
            return $e;
        }

        return new OrderResource($order);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return new OrderResource($order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    
    public function update(Request $request, Order $order)
    {

        $event = $request->get('evento');
        
        try {
            DB::beginTransaction();

            if ( $request->has('sucursal_id')){
                $order->sucursal()->associate($request->get('sucursal_id'));
            }else {
                $order->sucursal_id = null;
            }

            if($request->has('fecha_entrega_acordada')){
                $order->fecha_entrega_acordada = Carbon::createFromFormat('d-m-Y', $request->get('fecha_entrega_acordada'));
            }else {
                $order->fecha_entrega_acordada = null;
            }

            if($request->has('deliveryshift_id')){
                $order->deliveryshift()->associate($request->get('deliveryshift_id'));
            }else {
                $order->deliveryshift_id = null;
            }

            if ( $request->has('is_delivery') && boolval($request->get('is_delivery'))) {
                $order->is_delivery = true;
            }else {
                $order->is_delivery = false;
            }

            if($request->has('ivacondition_id')){
                $order->ivacondition()->associate($request->get('ivacondition_id'));
            }else {
                $order->ivacondition_id = null;
            }


            if ( ( $event == 'GUARDAR' && $order->state == 'EDITANDO' ) || ( $event == 'FINALIZAR' && $order->state == 'EDITANDO' ) || ( $event == 'FINALIZAR PREPARACION' && $order->state == 'EN PREPARACION' ) ) {

                $order->orderitems()->delete();
                $items = $request->get('items');
                foreach($items as $item){
                    $orderItem = new Orderitem;

                    $orderItem->order()->associate($order);
                    $orderItem->saleproduct()->associate($item['saleproduct_id']);
                    $orderItem->precio = $item['precio'];
                    $orderItem->cantidad = $item['cantidad'];

                    if($orderItem->saleproduct->stockproduct->is_stock_unitario_variable){
                        if ( $item['cantidad_total']) {
                            $orderItem->cantidad_total = $item['cantidad_total'];
                        }else {
                            $orderItem->cantidad_total = 0;
                        }
                    }

                    $orderItem->save();
                }

                $order->ordercomboitems()->delete();

                $comboitems = $request->get('comboitems');
                foreach($comboitems as $comboitem){
                    $combo = Combo::find($comboitem['combo_id']);

                    $ordercomboitem = new Ordercomboitem;
                    $ordercomboitem->order_id = $order->id;
                    $ordercomboitem->precio = $comboitem['precio'];
                    $ordercomboitem->combo_id = $comboitem['combo_id'];
                    $ordercomboitem->cantidad = $comboitem['cantidad'];

                    $ordercomboitem->save();
                    
                    
                    foreach($comboitem['comboitems'] as $combo_item_order) {

                        foreach($combo_item_order['saleproducts'] as $saleproduct_order){

                            $ordercombosaleproduct = new Ordercombosaleproduct;
                            $ordercombosaleproduct->cantidad = $saleproduct_order['cantidad'];
                            $ordercombosaleproduct->saleproduct()->associate($saleproduct_order['saleproduct_id']);
                            $ordercombosaleproduct->ordercomboitem()->associate($ordercomboitem->id);

                            $ordercombosaleproduct->save();
                        }
                        
                    }



                    $ordercomboitem->save();
                }

                if ( $event == 'FINALIZAR' ) {
                    $order->state = 'FINALIZADO';
                }

            }

            if ( ( $event == 'GUARDAR' && $order->state == 'EN PREPARACION' ) || ( $event == 'FINALIZAR PREPARACION' && $order->state == 'EN PREPARACION' ) ) {
                $items = $request->get('items');
                foreach($items as $item){

                    foreach ( $order->orderitems as $orderitem ) {
                        if ( $orderitem->saleproduct_id == $item['saleproduct_id'] ) {

                            if ( $orderitem->is_prepared && !boolval($item['is_prepared']) ) {
                                //devolver stock
                                $this->devolver_stock ( $orderitem->saleproduct, $order->sucursal, $orderitem->cantidad );
                            }

                            if ( !$orderitem->is_prepared && boolval($item['is_prepared']) ) {
                                //tomar stock
                                $this->tomar_stock ( $orderitem->saleproduct, $order->sucursal, $orderitem->cantidad );
                            }

                            $orderitem->is_prepared = boolval($item['is_prepared']);
                            $orderitem->save();
                        }
                    }
                }

                $comboitems = $request->get('comboitems');
                foreach($comboitems as $comboitem){                    
                    
                    foreach($comboitem['comboitems'] as $combo_item_order) {
                        
                        foreach($combo_item_order['saleproducts'] as $saleproduct_order){
                            
                            foreach ( $order->ordercomboitems as $ordercomboitem ) {
                                
                                if ( $ordercomboitem->combo_id == $comboitem['combo_id'] ) {
                                    foreach ( $ordercomboitem->ordercombosaleproducts as $ordercombosaleproduct ) {
                                        if ( $ordercombosaleproduct->saleproduct_id == $saleproduct_order['saleproduct_id'] ) {
                                            if ( $ordercombosaleproduct->is_prepared && !boolval($saleproduct_order['is_prepared']) ) {
                                                //devolver stock
                                                $this->devolver_stock ( $ordercombosaleproduct->saleproduct, $order->sucursal, $ordercombosaleproduct->cantidad );
                                            }
                
                                            if ( !$ordercombosaleproduct->is_prepared && boolval($saleproduct_order['is_prepared']) ) {
                                                //tomar stock
                                                $this->tomar_stock ( $ordercombosaleproduct->saleproduct, $order->sucursal, $ordercombosaleproduct->cantidad );
                                            }
                                            $ordercombosaleproduct->is_prepared = $saleproduct_order['is_prepared'];
                                            $ordercombosaleproduct->save();
                                        }
                                    }
                                }
                            }
                        }
                        
                    }
                }
            }

            if ( ( $event == 'EDITAR' && $order->state == 'FINALIZADO' ) || ( $event == 'EDITAR' && $order->state == 'CONFIRMADO' ) ) {
                $order->state = 'EDITANDO';
            }
            if ( ( $event == 'CONFIRMAR' && $order->state == 'FINALIZADO' ) ) {
                $order->state = 'CONFIRMADO';
            }

            if ( ( $event == 'INICIAR PREPARACION' && $order->state == 'CONFIRMADO' ) ) {
                $order->state = 'EN PREPARACION';
            }

            if ( ( $event == 'CANCELAR PREPARACION' && $order->state == 'EN PREPARACION' ) ) {

                foreach ( $order->orderitems as $orderitem ) {
                    if ( $orderitem->is_prepared ) {
                        //devolver stock
                        $this->devolver_stock ( $orderitem->saleproduct, $order->sucursal, $orderitem->cantidad );

                        $orderitem->is_prepared = false;
                        $orderitem->save();
                    }
                    
                }
                foreach ( $order->ordercomboitems as $ordercomboitem ) {
                    foreach ( $ordercomboitem->ordercombosaleproducts as $ordercombosaleproduct ) {
                        if ( $ordercombosaleproduct->is_prepared ) {
                            //devolver stock
                            $this->devolver_stock ( $ordercombosaleproduct->saleproduct, $order->sucursal, $ordercombosaleproduct->cantidad );

                            $ordercombosaleproduct->is_prepared = false;
                            $ordercombosaleproduct->save();
                        }
                    }
                }
                $order->state = 'CONFIRMADO';
                
            }

            if ( ( $event == 'FINALIZAR PREPARACION' && $order->state == 'EN PREPARACION' ) ) {
                $order->state = 'PREPARADO';
                
            }

            if ( ( $event == 'EDITAR' && $order->state == 'PREPARADO' ) ) {

                foreach ( $order->orderitems as $orderitem ) {
                    if ( $orderitem->is_prepared ) {
                        //devolver stock
                        $this->devolver_stock ( $orderitem->saleproduct, $order->sucursal, $orderitem->cantidad );

                        $orderitem->is_prepared = false;
                        $orderitem->save();
                    }
                    
                }
                foreach ( $order->ordercomboitems as $ordercomboitem ) {
                    foreach ( $ordercomboitem->ordercombosaleproducts as $ordercombosaleproduct ) {
                        if ( $ordercombosaleproduct->is_prepared ) {
                            //devolver stock
                            $this->devolver_stock ( $ordercombosaleproduct->saleproduct, $order->sucursal, $ordercombosaleproduct->cantidad );

                            $ordercombosaleproduct->is_prepared = false;
                            $ordercombosaleproduct->save();
                        }
                    }
                }
                $order->state = 'EDITANDO';
                
            }

            if ( ( $event == 'FACTURAR' && $order->state == 'PREPARADO' ) ) {

                $order->save();

                $sale = Sale::create();

                $sale->user()->associate(auth()->user());
                $sale->sucursal()->associate($order->sucursal_id);

                $sale->total = $request->get('total');

                $sale->client()->associate($order->client_id);
                $client = $order->client;

                $saldo_cliente = $client->saldo;
                $saldo_cliente = round($saldo_cliente + $sale->total, 4, PHP_ROUND_HALF_UP);
                $sale->saldo = $saldo_cliente;

                foreach($order->orderitems as $item){
                    $saleItem = new Saleitem;
    
                    $saleItem->sale()->associate($sale);
                    $saleItem->saleproduct()->associate($item->saleproduct_id);
                    $saleItem->precio = $item->precio;
                    $saleItem->cantidad = $item->cantidad;
                    $saleItem->ivaaliquot_id = $saleItem->saleproduct->stockproduct->ivaaliquot->id;
                    if($saleItem->saleproduct->stockproduct->is_stock_unitario_variable){
                        $saleItem->cantidad_total = $item->cantidad_total;
                    }
    
                    $saleItem->save();
                }

                $client->saldo = $saldo_cliente;
                $client->save();
    
                $sale->save();

                $order->sale()->associate($sale->id);

                $order->state = 'FACTURADO';
                //return $sale;
            }

            //------------------           

            $order->save();
            
            usleep(500000);

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
            return $e;
        }

        return new OrderResource($order);
    }

    private function tomar_stock ( $saleproduct, $sucursal, $cantidad ) {
        $stockSucursal = Stocksucursal::where('stockproduct_id', $saleproduct->stockproduct_id)
            ->where('sucursal_id', $sucursal->id)
            ->first();
        if ( !$stockSucursal ) {
            $stockSucursal = Stocksucursal::create();
            $stockSucursal->stock = 0;
            $stockSucursal->stockproduct()->associate($saleproduct->stockproduct_id);
            $stockSucursal->sucursal()->associate($sucursal->id);
            $stockSucursal->save();

        }
        $stockSucursal->stock = $stockSucursal->stock - round($cantidad * $saleproduct->relacion_venta_stock, 6, PHP_ROUND_HALF_UP);

        $stockSucursal->save();
    }

    private function devolver_stock ( $saleproduct, $sucursal, $cantidad ) {
        $stockSucursal = Stocksucursal::where('stockproduct_id', $saleproduct->stockproduct_id)
            ->where('sucursal_id', $sucursal->id)
            ->first();
        if ( !$stockSucursal ) {
            $stockSucursal = Stocksucursal::create();
            $stockSucursal->stock = 0;
            $stockSucursal->stockproduct()->associate($saleproduct->stockproduct_id);
            $stockSucursal->sucursal()->associate($sucursal->id);
            $stockSucursal->save();

        }
        $stockSucursal->stock = $stockSucursal->stock + round($cantidad * $saleproduct->relacion_venta_stock, 6, PHP_ROUND_HALF_UP);

        $stockSucursal->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

    public function get_order_check_sale ( $order_id ) 
    {
        $order = Order::findOrFail($order_id);

        return new OrderCheckSaleResource($order);
    }

    public function update_precios ( Request $request, $order_id ) {
        $order = Order::findOrFail($order_id);
        //return $request->all();
        try {
            DB::beginTransaction();
            $items = $request->get('data')['items'];
            foreach ( $items as $item ) {
                if ( $item['tipo'] == 'saleproduct' && $item['actualizar_precio'] ) {
                    foreach ( $order->orderitems as $orderitem ) {
                        if ( $item['id'] == $orderitem->id ) {
                            $orderitem->precio = $item['precio_actualizado'];
                            
                            $orderitem->save();
                            
                        }
                    }
                }else {
                    foreach ( $order->ordercomboitems as $ordercomboitem ) {
                        if ( $item['id'] == $ordercomboitem->id ) {
                            $ordercomboitem->precio = $item['precio_actualizado'];
                            $ordercomboitem->save();
                        }
                    }
                }
            }
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
            return $e;
        }

        return new OrderResource($order);
    }
}
