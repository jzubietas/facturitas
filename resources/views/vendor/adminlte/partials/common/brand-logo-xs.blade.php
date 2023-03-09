@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')
@php( $profile_url = View::getSection('profile_url') ?? config('adminlte.profile_url', 'logout') )
@php( $dashboard_url = View::getSection('dashboard_url') ?? config('adminlte.dashboard_url', 'home') )

@if (config('adminlte.use_route_url', false))
  @php( $dashboard_url = $dashboard_url ? route($dashboard_url) : '' )
@else
  @php( $dashboard_url = $dashboard_url ? url($dashboard_url) : '' )
@endif

<div style="overflow: hidden; display: flex; flex-wrap: wrap"
     @if($layoutHelper->isLayoutTopnavEnabled())
       class="navbar-brand {{ config('adminlte.classes_brand') }}"
     @else
       class="brand-link {{ config('adminlte.classes_brand') }}"
  @endif>

  {{-- Small brand logo--}}
  {{--    <img src="{{ asset(config('adminlte.logo_img', 'vendor/adminlte/dist/img/AdminLTELogo.png')) }}"
           alt="{{ config('adminlte.logo_img_alt', 'AdminLTE') }}"
           class="{{ config('adminlte.logo_img_class', 'brand-image img-circle elevation-3') }}"
           style="opacity:.8">

      <span class="brand-text font-weight-light {{ config('adminlte.classes_brand_text') }}" style="position: relative;">
          {!! config('adminlte.logo', '<b>Admin</b>LTE') !!}
      </span>--}}


  @if(config('adminlte.usermenu_image'))
    <img src="{{ Auth::user()->adminlte_image() }}"
         class="brand-image img-circle elevation-3"
         alt="{{ Auth::user()->name }}">
  @endif
  <span @if(config('adminlte.usermenu_image')) class="brand-text font-weight-light"
        style="position: relative; font-size: 14px; display: flex;"@endif>
      <p class="d-flex justify-content-center align-items-center flex-column text-uppercase">
        <?php
        $cadena = Auth::user()->name;
        $arr = explode(' ', trim($cadena))
        ?>
        {{$arr[0]}}
      <sub>{{ Auth::user()->adminlte_desc() }}</sub>
      </p>

      {{--PERFIL ROUTE--}}
      <a href="#" class="ml-2 "
         style="background: #f8f9fa; border: 1px solid #ddd; padding: 5px 9px; border-radius: 100%; cursor: pointer">
        <i class="fa fa-fw fa-user text-lightblue"></i>
      </a>

      <a href="#" class="ml-2 "
         style="background: #f8f9fa; border: 1px solid #ddd; padding: 5px 9px; border-radius: 100%; cursor: pointer"
         onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fa fa-fw fa-power-off text-red"></i>
      </a>

        </span>


</div>
