<li class="nav-item">
    <a class="nav-link" data-widget="pushmenu" href="#"
       @if(config('adminlte.sidebar_collapse_remember'))
           data-enable-remember="true"
       @endif
       @if(!config('adminlte.sidebar_collapse_remember_no_transition'))
           data-no-transition-after-reload="false"
       @endif
       @if(config('adminlte.sidebar_collapse_auto_size'))
           data-auto-collapse-size="{{ config('adminlte.sidebar_collapse_auto_size') }}"
        @endif>
        <i class="fas fa-bars"></i>
        <span class="sr-only">{{ __('adminlte::adminlte.toggle_navigation') }}</span>
    </a>
</li>
@if(user_rol(\App\Models\User::ROL_ASESOR)||user_rol(\App\Models\User::ROL_ADMIN))
    <li class="nav-item ml-2">
        <a class="nav-link btn btn-warning btn-sm" href="#" data-toggle="addalert">
            <b class="text-black font-weight-bold">
                <i class="fas fa-bell"></i>
                Agregar Alerta
            </b>
        </a>
    </li>
@endif

@if(user_rol(\App\Models\User::ROL_ASESOR)||user_rol(\App\Models\User::ROL_ADMIN))
  <li class="nav-item ml-2">
    <a class="nav-link btn btn-info btn-sm" href="#" data-toggle="contactoalert">
      <b class="text-white font-weight-bold">
        <i class="fas fa-user-plus p-1"></i>
        Agregar Contacto
      </b>
    </a>
  </li>
@endif
