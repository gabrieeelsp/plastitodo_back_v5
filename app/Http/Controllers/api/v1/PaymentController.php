<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

use App\Models\Caja;
use App\Models\Sale;

use App\Http\Requests\v1\payments\CreatePaymentRequest;

use App\Http\Resources\v1\sales\PaymentResource;

use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
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
    public function store(CreatePaymentRequest $request)
    {
        $data = $request->get('data');

        $caja = Caja::find($data['relationships']['caja']['data']['id']);

        if ( !$caja->is_open ){
            return response()->json(['message' => 'Caja Cerrada'], 422);
        }

        $sale = Sale::find($data['relationships']['sale']['data']['id']);

        try{
            DB::beginTransaction();

            $salePayment = new Payment;

            $salePayment->sale()->associate($sale);
            $salePayment->paymentmethod()->associate($data['relationships']['paymentmethod']['data']['id']);
            $salePayment->caja()->associate($caja);

            $salePayment->valor = $data['attributes']['valor'];

            if ( $sale->client ){
                $saldo_cliente = $sale->client->saldo;
                $saldo_cliente = $saldo_cliente - $salePayment->valor;

                $salePayment->saldo = $saldo_cliente;
                $sale->client->save();
            }

            $salePayment->save();

            usleep(1000000);
            
            DB::commit();
            // all good

        }  catch (\Exception $e) {
            DB::rollback();
            return $e;
            // something went wrong
        } 
        return new PaymentResource($salePayment);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
