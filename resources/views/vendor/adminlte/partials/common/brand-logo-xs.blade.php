@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

@php( $dashboard_url = View::getSection('dashboard_url') ?? config('adminlte.dashboard_url', 'home') )

@if (config('adminlte.use_route_url', false))
    @php( $dashboard_url = $dashboard_url ? route($dashboard_url) : '' )
@else
    @php( $dashboard_url = $dashboard_url ? url($dashboard_url) : '' )
@endif

<a href="{{ $dashboard_url }}" style="overflow: hidden;"
    @if($layoutHelper->isLayoutTopnavEnabled())
        class="navbar-brand {{ config('adminlte.classes_brand') }}"
    @else
        class="brand-link {{ config('adminlte.classes_brand') }}"
    @endif>

    {{-- Small brand logo --}}
    <img src="{{ asset(config('adminlte.logo_img', 'vendor/adminlte/dist/img/AdminLTELogo.png')) }}"
         alt="{{ config('adminlte.logo_img_alt', 'AdminLTE') }}"
         class="{{ config('adminlte.logo_img_class', 'brand-image img-circle elevation-3') }}"
         style="opacity:.8">
    <!--<img src="{{asset('imagenes/ojo-logo.svg')}}" width="120">-->
    <img src="{{asset('imagenes/papel-picado.png')}}" id="gorro_navidad">
    {{-- Brand text --}}
    <span class="brand-text font-weight-light {{ config('adminlte.classes_brand_text') }}" style="position: relative;">
        {!! config('adminlte.logo', '<b>Admin</b>LTE') !!}

    </span>
        <img src="{{asset('imagenes/globo.png')}}" id="santa_navidad">
</a>
