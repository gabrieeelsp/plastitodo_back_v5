<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Refund;
use Illuminate\Http\Request;

use App\Models\Caja;
use App\Models\Sale;

use App\Http\Requests\v1\refunds\CreateRefundRequest;

use App\Http\Resources\v1\sales\RefundResource;

use Illuminate\Support\Facades\DB;

class RefundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRefundRequest $request)
    {
        $data = $request->get('data');

        $caja = Caja::find($data['relationships']['caja']['data']['id']);

        if ( !$caja->is_open ){
            return response()->json(['message' => 'Caja Cerrada'], 422);
        }

        $sale = Sale::find($data['relationships']['sale']['data']['id']);

        try{
            DB::beginTransaction();

            $saleRefund = new Refund;

            $saleRefund->sale()->associate($sale);
            $saleRefund->paymentmethod()->associate($data['relationships']['paymentmethod']['data']['id']);
            $saleRefund->caja()->associate($caja);

            $saleRefund->valor = $data['attributes']['valor'];

            if ( $sale->client ){
                $saldo_cliente = $sale->client->saldo;
                $saldo_cliente = $saldo_cliente + $saleRefund->valor;

                $saleRefund->saldo = $saldo_cliente;
                $sale->client->save();
            }

            $saleRefund->save();

            usleep(1000000);
            
            DB::commit();
            // all good

        }  catch (\Exception $e) {
            DB::rollback();
            return $e;
            // something went wrong
        } 
        return new RefundResource($saleRefund);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Refund  $refund
     * @return \Illuminate\Http\Response
     */
    public function show(Refund $refund)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Refund  $refund
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Refund $refund)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Refund  $refund
     * @return \Illuminate\Http\Response
     */
    public function destroy(Refund $refund)
    {
        //
    }
}
