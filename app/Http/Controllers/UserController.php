<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mirol=Auth::user()->rol;
        if(Auth::user()->rol == "Encargado"){
            $users = User::where('users.supervisor', Auth::user()->id)
                            ->where('users.rol', 'Asesor')
                            ->get();
        }
        else if(Auth::user()->rol == "Jefe de operaciones"){
            $users = User::where('users.jefe', Auth::user()->id)
                            ->where('users.rol', 'Operario')
                            ->get();
        /*}else if(Auth::user()->rol == "Llamadas"){
            $users = User::where('users.jefe', Auth::user()->id)
                            ->where('users.rol', 'Operario')
                            ->where('users.estado', '0')
                            ->get();
        }*/
    }   else{
            $users = User::all();
        }

        $superasesor = User::where('rol', 'Super asesor')->count();

        return view('usuarios.index', compact('users', 'superasesor','mirol'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::get();

        return view('usuarios.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'prole_id' => 'required',
            'password' => 'required',
            'confirm_password' => 'required|same:password'
        ]);

        $files = $request->file('imagen');
        $destinationPath = base_path('public/storage/users/');
        
        if(isset($files)){
            $file_name = Carbon::now()->second.$files->getClientOriginalName();
            $files->move($destinationPath , $file_name);
        }
        else{
            $file_name = 'logo_facturas.png';
        }
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'rol' => $request->role_name,
            'password' => bcrypt($request->password),
            'identificador' => $request->identificador,
            'celular' => $request->celular,
            'provincia' => $request->provincia,
            'distrito' => $request->distrito,
            'direccion' => $request->direccion,
            'referencia' => $request->referencia,
            'profile_photo_path' => $file_name,
            'estado' => '1'
        ]);

        $user->roles()->sync($request->role_id);

        return redirect()->route('users.index')->with('info', 'registrado');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $mirol=Auth::user()->rol;
        $roles = Role::get();
        return view('usuarios.edit', compact('user', 'roles','mirol'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
        ]);

        $files = $request->file('imagen');
        $destinationPath = base_path('public/storage/users/');

        if(isset($files)){
            $file_name = Carbon::now()->second.$files->getClientOriginalName();
            $files->move($destinationPath , $file_name);
        }
        else{
            $file_name = $user->profile_photo_path;
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'identificador' => $request->identificador,
            'celular' => $request->celular,
            'provincia' => $request->provincia,
            'distrito' => $request->distrito,
            'direccion' => $request->direccion,
            'referencia' => $request->referencia,
            'profile_photo_path' => $file_name
        ]);

        if ($request->prole_id != " " && $request->role_name != "") {
            $user->roles()->sync($request->role_id);

            $user->update([
                'rol' => $request->role_name
            ]);
        }

        return redirect()->route('users.index')->with('info', 'actualizado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
        $user->update([
            'estado' => $request->estado
        ]);
        return redirect()->route('users.index')->with('info', 'eliminado');
    }

    public function reset(User $user)
    {
        $user->update([
            'password' => bcrypt('123456789')
        ]);

        return redirect()->route('users.index')->with('info', 'reseteado');
    }

    public function Llamadas()
    {   
        //si es usuario llamadas  solo salga sus asesores
        $users = User::where('rol', 'Asesor')
                    ->where('estado', '1')
                    ->get();
        $supervisores = User::where('rol', 'Encargado')
                    ->where('estado', '1')
                    ->pluck('name', 'id');
        $jefellamadas = User::where('rol', 'Jefe de llamadas')
                    ->where('estado', '1')
                    ->pluck('name', 'id');
        $asesores = User::where('rol', 'Asesor')
                    ->where('estado', '1')
                    ->pluck('name', 'id');
        $supervisor = User::where('rol', 'Encargado')
                    ->where('estado', '1')
                    ->get();
        $operarios = User::where('rol', 'Operario')
                    ->where('estado', '1')
                    ->pluck('name', 'id');
        $superasesor = User::where('rol', 'Super asesor')->count();
        
        return view('usuarios.llamadas', compact('users', 'supervisores', 'supervisor', 'operarios', 'superasesor','jefellamadas','asesores'));
    }

    public function Llamadastabla(Request $request)
    {
        $users = User::where('rol', 'Llamadas')
                    ->where('estado', '1')
                    ->orderBy('created_at', 'DESC')
                    ->get();

        return Datatables::of($users)
                    ->addIndexColumn()
                    ->addColumn('action', function($user){     
                        $btn="";
                        $btn = $btn.'<a href="" data-target="#modal-asignarjefellamadas" data-toggle="modal" data-jefellamadas="'.$user->id.'"><button class="btn btn-info btn-sm"><i class="fas fa-check"></i> Asignar Jefe Llamadas</button></a>';
                        //$btn = $btn.'<a href="" data-target="#modal-asignarasesor" data-toggle="modal" data-supervisor="'.$user->id.'"><button class="btn btn-warning btn-sm"><i class="fas fa-check"></i> Asignar Asesor</button></a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
    }

    public function Asesorcombo(Request $request)
    {   
        $mirol=Auth::user()->rol;
        $users = null;
        $users = User::where('estado', '1')->where("rol","Asesor");

        if($mirol=='Llamadas')
        {
            $users = $users->where('llamada',Auth::user()->id)->where("rol","Asesor");
        }else if($mirol=='Jefe de llamadas'){
            $users = $users->where('llamada',Auth::user()->id)->where("rol","Asesor");
        }else if($mirol=='Asesor'){
            $users = $users->where('id',Auth::user()->id)->where("rol","Asesor");
        }else{
            $usersB=User::where("identificador","B")->where("rol","Administrador");
            $users = $usersB->union($users);
        }
        $users=$users->orderBy('exidentificador', 'ASC')->get();
        $html="";
        //$html = '<option value="">' . trans('---- SELECCIONE ASESOR ----') . '</option>';
        foreach ($users as $user) 
        {
            if($user->identificador=='B')
            {
                $html .= '<option style="color:black" value="' . $user->identificador . '">' . $user->identificador. '</option>';
            }else{
                if(intval($user->exidentificador)%2==0)
                {
                    $html .= '<option disabled style="color:red" value="' . $user->identificador . '">' . $user->identificador.  ( ($user->exidentificador!=null)? '  (' . $user->exidentificador.')':'' )  . '</option>';
                }else{
                    $html .= '<option style="color:black" value="' . $user->identificador . '">' . $user->identificador. ( ($user->exidentificador!=null)? '  (' . $user->exidentificador.')':'' ) . '</option>';
                }
            }
        }
        
        return response()->json(['html' => $html]);

        //return response()->json($users);
    }

    public function Asesorcombopago(Request $request)
    {   
        $mirol=Auth::user()->rol;
        $users = null;
        $users = User::where('estado', '1')->where("rol","Asesor");

        if($mirol=='Llamadas')
        {
            $users = $users->where('llamada',Auth::user()->id)->where("rol","Asesor");

        }else if($mirol=='Jefe de llamadas'){

            $users = $users->where('llamada',Auth::user()->id)->where("rol","Asesor");
        }else if($mirol=='Asesor'){

            $users = $users->where('id',Auth::user()->id)->where("rol","Asesor");
        }else{
            
            $usersB=User::where("identificador","B")->where("rol","Administrador");
            $users = $usersB->union($users);
        }
        $users=$users->orderBy('exidentificador', 'ASC')->get();
        $html="";
        //$html = '<option value="">' . trans('---- SELECCIONE ASESOR ----') . '</option>';
        foreach ($users as $user) 
        {
            if($user->identificador=='B')
            {
                $html .= '<option style="color:black" value="' . $user->identificador . '">' . $user->identificador. '</option>';
            }else{
                if(intval($user->exidentificador)%2==0)
                {
                    $html .= '<option style="color:red" value="' . $user->identificador . '">' . $user->identificador.  ( ($user->exidentificador!=null)? '  (' . $user->exidentificador.')':'' )  . '</option>';
                }else{
                    $html .= '<option style="color:black" value="' . $user->identificador . '">' . $user->identificador. ( ($user->exidentificador!=null)? '  (' . $user->exidentificador.')':'' ) . '</option>';
                }
            }
        }
        
        return response()->json(['html' => $html]);

        //return response()->json($users);
    }

    public function Asesores()
    {   
        $users = User::where('rol', 'Asesor')
                    ->where('estado', '1')
                    ->get();
        $asesores = User::where('rol', 'Asesor')
                    ->where('estado', '1')
                    ->get();
        $supervisores = User::where('rol', 'Encargado')
                    ->where('estado', '1')
                    ->pluck('name', 'id');
        $supervisor = User::where('rol', 'Encargado')
                    ->where('estado', '1')
                    ->get();
        $encargados = User::where('rol', 'Encargado')
                    ->where('estado', '1')
                    ->pluck('name', 'id');
        $supervisores = User::where('rol', 'Encargado')
                    ->where('estado', '1')
                    ->get();
        $operarios = User::where('rol', 'Operario')
                    ->where('estado', '1')
                    ->pluck('name', 'id');
        $llamadas = User::whereIn('rol', ['Llamadas', 'Jefe de llamadas'])
                    ->where('estado', '1')
                    ->pluck('name', 'id');
        $superasesor = User::where('rol', 'Super asesor')->count();
        
        return view('usuarios.asesores', compact('users', 'supervisores', 'supervisor', 'operarios', 'superasesor','supervisores','asesores','encargados','llamadas'));
        
    }

    public function Asesorestabla(Request $request)
    {
        $users = User::where('rol', 'Asesor')
                    ->where('estado', '1')
                    ->get();

        $users = User::leftjoin('users as encargado', 'users.supervisor', 'encargado.id')
            ->leftjoin('users as operario', 'users.operario', 'operario.id')
            ->leftjoin('users as llamada', 'users.llamada', 'llamada.id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'encargado.name as encargado',
                'operario.name as operario',
                'llamada.name as llamada',
                'users.estado',
                //DB::raw('DATE_FORMAT(users.created_at, "%d/%m/%Y") as fecha'),
            )
            ->where('users.rol', 'Asesor')
            ->where('users.estado', '1')
            ->groupBy(
                'users.id',
                'users.name',
                'users.email',
                'encargado.name',
                'operario.name',
                'llamada.name',
                'users.estado',
                'users.created_at',
            )
            //->orderBy('users.created_at', 'DESC')
            ->get();

        return Datatables::of($users)
                ->addIndexColumn()
                ->addColumn('action', function($user){     
                    $btn="";
                    $btn = $btn.'<a href="" data-target="#modal-asignarencargado" data-toggle="modal" data-encargado="'.$user->id.'"><button class="btn btn-info btn-sm"><i class="fas fa-check"></i> Asignar Encargado</button></a>';
                    $btn = $btn.'<a href="" data-target="#modal-asignaroperario" data-toggle="modal" data-operario="'.$user->id.'"><button class="btn btn-warning btn-sm"><i class="fas fa-check"></i> Asignar Operario</button></a>';
                    $btn = $btn.'<a href="" data-target="#modal-asignarllamadas" data-toggle="modal" data-llamadas="'.$user->id.'"><button class="btn btn-success btn-sm"><i class="fas fa-check"></i> Asignar Llamadas</button></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
    }

    

    public function AsignarSupervisor(Request $request, User $user)
    {
        $user->update([
            'supervisor' => $request->supervisor
        ]);

        return redirect()->route('users.asesores')->with('info', 'asignado');
    }

    public function AsignarJefellamadaspost(Request $request)
    {
        if (!$request->hiddenIdjefellamadas) {
            $html="";

        }else{
            $jefellamadas=$request->jefellamadas;
            $buscar=$request->hiddenIdjefellamadas;

            $html=$buscar."|".$jefellamadas;

            $user=User::find($request->hiddenIdjefellamadas);
            $user->update([
                'supervisor' => $request->jefellamadas
            ]);
        }
        
        return response()->json(['html' => $html]);
        //return redirect()->route('users.asesores')->with('info', 'asignado');
    }

    public function AsignarLlamadaspost(Request $request)
    {
        if (!$request->hiddenIdllamadas) {
            $html="";

        }else{
            $llamadas=$request->llamadas;
            $buscar=$request->hiddenIdllamadas;

            $html=$buscar."|".$llamadas;//44/49

            $user=User::find($buscar);
            $user->update([
                'llamada' => $llamadas
            ]);
        }
        
        return response()->json(['html' => $html]);
        //return redirect()->route('users.asesores')->with('info', 'asignado');
    }

    public function AsignarOperario(Request $request, User $user)
    {
        $jefe = User::find($request->operario, ['jefe']);
        $user->update([
            'operario' => $request->operario,
            'jefe' => $jefe->jefe
        ]);

        return redirect()->route('users.asesores')->with('info', 'asignado');
    }

    public function AsignarOperariopost(Request $request)
    {
        if (!$request->hiddenIdasesor) {
            $html="";

        }else{
            $asesor=$request->asesor;
            $buscar=$request->hiddenIdasesor;

            $html=$buscar."|".$asesor;
            $user=User::find($request->hiddenIdasesor);
            $jefe = User::find($request->asesor, ['jefe']);
            $user->update([
                'operario' => $request->asesor,
                'jefe' => $jefe->jefe
            ]);

        }
        
        return response()->json(['html' => $html]);
        //return redirect()->route('users.asesores')->with('info', 'asignado');
    }

    public function AsignarAsesorpost(Request $request)
    {
        if (!$request->hiddenIdasesor) {
            $html="";

        }else{
            $asesor=$request->asesor;
            $buscar=$request->hiddenIdasesor;

            $html=$buscar."|".$asesor;
            $user=User::find($request->hiddenIdasesor);
            $jefe = User::find($request->asesor, ['jefe']);
            $user->update([
                'operario' => $request->asesor,
                'jefe' => $jefe->jefe
            ]);

        }
        
        return response()->json(['html' => $html]);
        //return redirect()->route('users.asesores')->with('info', 'asignado');
    }

    public function AsignarSupervisorpost(Request $request)
    {
        if (!$request->hiddenIdasesor) {
            $html="";

        }else{
            $asesor=$request->asesor;
            $buscar=$request->hiddenIdasesor;

            $html=$buscar."|".$asesor;
            $user=User::find($request->hiddenIdasesor);
            $jefe = User::find($request->asesor, ['jefe']);
            $user->update([
                'operario' => $request->asesor,
                'jefe' => $jefe->jefe
            ]);

        }
        
        return response()->json(['html' => $html]);
        //return redirect()->route('users.asesores')->with('info', 'asignado');
    }

    public function AsignarJefe(Request $request, User $user)
    {
        $user->update([
            'jefe' => $request->supervisor
        ]);

        return redirect()->route('users.operarios')->with('info', 'asignado');
    }

    public function MisAsesores()
    {
        $users = User::where('rol', 'Asesor')
                    ->where('supervisor', Auth::user()->id)
                    ->where('estado', '1')
                    ->get();
        $superasesor = User::where('rol', 'Super asesor')->count();

        return view('usuarios.misasesores', compact('users', 'superasesor'));
    }

    public function AsignarMetaAsesor(Request $request, User $user)
    {
        $user->update([
            'meta_pedido' => $request->meta_pedido,
            'meta_cobro' => $request->meta_cobro,
        ]);

        return redirect()->route('users.misasesores')->with('info', 'asignado');
    }

    public function MiPersonal()
    {
            $encargados = User::where('rol', 'Encargado')
                        ->where('estado', '1')
                        ->pluck('name', 'id');
            $asesores = User::where('rol', 'Asesor')
                        ->where('estado', '1')
                        ->get();       
            $jefellamadas = User::whereIn('rol', 'Jefe de llamadas')
                        ->where('estado', '1')
                        ->get();    
            $llamadas = User::whereIn('rol', 'Llamadas')
                        ->where('estado', '1')
                        ->get(); 
            $jefeoperarios = User::where('rol', 'Jefe de operaciones')
                        ->where('estado', '1')
                        ->get();
            $operarios = User::where('rol', 'Operario')
                        ->where('estado', '1')
                        ->get();
                        /*->pluck('name', 'id');*/
            /*$llamadas = User::whereIn('rol', ['Llamadas', 'Jefe de llamadas'])
                        ->where('estado', '1')
                        ->pluck('name', 'id');*/
            $superasesor = User::where('rol', 'Super asesor')->count();
            
            return view('usuarios.mipersonal', compact('encargados', 'asesores', 'jefellamadas', 'llamadas', 'jefeoperarios','operarios','superasesor'));
    }

    public function Encargados()
    {
        $users = User::where('rol', 'Encargado')
                    ->where('estado', '1')
                    ->get();
        $superasesor = User::where('rol', 'Super asesor')->count();

        return view('usuarios.encargados', compact('users', 'superasesor'));
    }

    public function Operarios()
    {
        $users = User::where('rol', 'Operario')
                    ->where('estado', '1')
                    ->get();
        $jefes = User::where('rol', 'Jefe de operaciones')
                    ->where('estado', '1')
                    ->pluck('name', 'id');
        $jefe = User::where('rol', 'Jefe de operaciones')
                    ->where('estado', '1')
                    ->get();
        $superasesor = User::where('rol', 'Super asesor')->count();

        return view('usuarios.operarios', compact('users', 'jefes', 'jefe', 'superasesor'));
    }

    public function MisOperarios()
    {
        $users = User::where('rol', 'Operario')
                    ->where('jefe', Auth::user()->id)
                    ->where('estado', '1')
                    ->get();
        $superasesor = User::where('rol', 'Super asesor')->count();

        return view('usuarios.misoperarios', compact('users', 'superasesor'));
    }

    public function Jefes()
    {
        $users = User::where('rol', 'Jefe de operaciones')
                    ->where('estado', '1')
                    ->get();
        $superasesor = User::where('rol', 'Super asesor')->count();

        return view('usuarios.jefes', compact('users', 'superasesor'));
    }

    public function AsignarMetaEncargado(Request $request, User $user)
    {
        $user->update([
            'meta_pedido' => $request->meta_pedido,
            'meta_cobro' => $request->meta_cobro,
        ]);

        return redirect()->route('users.encargados')->with('info', 'asignado');
    }
}
