<?php

namespace App\Http\Controllers;

use App\Models\PedidoHistory;
use Illuminate\Http\Request;

class PedidoHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(
          PedidoHistory::join('clientes as c', 'pedido_histories.cliente_id', 'c.id')
          ->where('pedido_histories.user_id', auth()->id())->get()
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'identificador' => 'required',
            'cliente_id' => 'required',
            'ruc' => 'required',
            'empresa' => 'required',
            'year' => 'required',
            'cantidad' => 'required',
            'tipo_banca' => 'required',
            'descripcion' => 'required',
            'nota' => 'required',
            'courier_price' => 'required',
        ]);
        $data = $request->all();
        foreach ($data as $key => $value) {
            $data[$key] = trim($value);
            //if()
        }
        $data['user_id'] = \auth()->id();
        //return $data;
        //$data['tipo_banca']=explode('_',$data['tipo_banca'])[0];
        return PedidoHistory::query()->updateOrCreate($data);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\PedidoHistory $pedidoHistory
     * @return \Illuminate\Http\Response
     */
    public function show(PedidoHistory $pedidoHistory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\PedidoHistory $pedidoHistory
     * @return \Illuminate\Http\Response
     */
    public function edit(PedidoHistory $pedidoHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\PedidoHistory $pedidoHistory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PedidoHistory $pedidoHistory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\PedidoHistory $pedidoHistory
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        return PedidoHistory::query()->where('id', $request->history_id)->delete();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\PedidoHistory $pedidoHistory
     * @return \Illuminate\Http\Response
     */
    public function destroy(PedidoHistory $pedidoHistory)
    {
        //
    }
}
