<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegisterIncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->has('datatable'))
        {
            $query=Cliente::query()->where('tipo',0)->activo()
            ->select([
                'created_at',
                'celular as basefria',
                'user_clavepedido as asesor'
            ]);
            return datatables()->query(DB::table($query))
                ->addIndexColumn()
                ->addColumn('action', function ($cliente)  {
                    $btn = [];
                    $btn []='<button type="button" class="btn btn-warning btn-sm btn_llamar"  data-basefria="' . $cliente->basefria . '"><i class="fa fa-phone"></i></a>';
                    return join('', $btn);
                })
                ->rawColumns(['action'])
                //->toJson();
                ->make(true);
        }
        return view('register_income.index');
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


}
