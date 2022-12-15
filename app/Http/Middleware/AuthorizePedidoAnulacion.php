<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorizePedidoAnulacion
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null ...$guards
     * @return mixed
     */
    public function handle(Request $request, \Closure $next, ...$guards)
    {
        $password = $request->anulacion_password;

        if(!\Hash::check($password,setting("pedido_password"))){
            abort(401);
        }

        return $next($request);
    }
}
