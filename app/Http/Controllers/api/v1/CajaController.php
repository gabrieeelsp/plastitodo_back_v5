<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Caja;
use Illuminate\Http\Request;
use App\Http\Resources\v1\cajas\CajaResource;
use App\Http\Resources\v1\cajas\CajaMinResource;

use Carbon\Carbon;

class CajaController extends Controller
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
    public function store(Request $request)
    {
        $data = $request->get('data');
        $caja = Caja::create([
            'dinero_inicial' => $data['dinero_inicial'],
        ]);

        $caja->user()->associate(auth()->user()->id);
        $caja->sucursal()->associate($data['sucursal_id']);

        $caja->save();

        return new CajaMinResource(Caja::find($caja->id));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Caja  $caja
     * @return \Illuminate\Http\Response
     */
    public function show(Caja $caja)
    {
        return new CajaResource($caja);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Caja  $caja
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Caja $caja)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Caja  $caja
     * @return \Illuminate\Http\Response
     */
    public function destroy(Caja $caja)
    {
        //
    }

    public function find($sucursal_id) {
        $atr = [];

        array_push($atr, ['sucursal_id', $sucursal_id] );

        array_push($atr, ['is_open', true] );

        array_push($atr, ['user_id', auth()->user()->id] );

        $caja = Caja::where($atr)->firstOrFail();

        return new CajaMinResource($caja);
    }

    private function have_caja_open() {
        $atr = [];

        array_push($atr, ['is_open', true] );

        array_push($atr, ['user_id', auth()->user()->id] );

        $caja = Caja::where($atr)->get();
        if ( count($caja) !=0 ) {
            return true;
        }
        return false;
    }

    public function cerrar (Request $request, $caja_id) {

        $caja = Caja::findOrFail($caja_id);

        $data = $request->get('data');

        $caja->dinero_final = $data['dinero_final'];

        $caja->close_at = Carbon::now();

        $caja->is_open = false;

        $caja->save();

        return new CajaResource(Caja::find($caja_id));

    }
}
