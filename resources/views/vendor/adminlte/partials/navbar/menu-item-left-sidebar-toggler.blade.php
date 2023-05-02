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
        <a class="nav-link btn btn-warning btn-sm m-0 d-flex a-navbar" href="#" data-toggle="addalert">
            <b class="text-black font-weight-bold d-flex align-items-center justify-content-center" style="grid-gap: 3px">
                <i class="fas fa-sticky-note"></i>
                <p class="m-0 text-card-navbar">Notas</p>
            </b>
        </a>
    </li>
@endif

@if(user_rol(\App\Models\User::ROL_ASESOR)||user_rol(\App\Models\User::ROL_ADMIN)||user_rol(\App\Models\User::ROL_ENCARGADO))
  <li class="nav-item ml-2">
    <a class="nav-link btn btn-info btn-sm m-0 d-flex a-navbar" href="#" data-target="#modal-agregar-contacto" data-toggle="modal">
      <b class="text-white font-weight-bold d-flex align-items-center justify-content-center">
        <i class="fas fa-user-plus p-1"></i>
        <p class="m-0 text-card-navbar">Agregar Contascto</p>
      </b>
    </a>
  </li>
@endif

@push('css')
  <style>
    @media screen and (max-width: 805px){
      .text-card-navbar{
        display: none !important;
      }
    }
  </style>
@endpush
