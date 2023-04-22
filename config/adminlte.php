<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => '',
    'title_prefix' => '',
    'title_postfix' => ' | Ojo Celeste',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_ico_only' => true,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => 'Ojo<b>Celeste</b>',
    'logo_img' => 'vendor/adminlte/dist/img/logo_facturas.png',
    'logo_img_class' => 'brand-image-xl img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'Ojo Celeste',

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => true,
    'usermenu_header_class' => 'bg-white',
    'usermenu_image' => true,
    'usermenu_desc' => true,
    'usermenu_profile_url' => true,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => true,
    'layout_fixed_navbar' => true,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => 'card-outline card-dark',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => 'd-none',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-success',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-light-dark elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-dark navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => true,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => 'dashboard',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For detailed instructions you can look the laravel mix section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'menu' => [
        // Navbar items:
        [
            'type' => 'fullscreen-widget',
            'topnav_right' => true,
        ],

        [
            'type' => 'navbar-notification',
            'id' => 'my-notification',
            'icon' => 'fas fa-envelope',
            /* 'label'        => rand(0, 10) ,*/
            'label_color' => 'danger',
            'route' => 'notifications.index',
            'topnav_right' => true,
            'dropdown_mode' => true,
            'dropdown_flabel' => 'Todas las notificaciones',
            'update_cfg' => [
                'route' => 'notifications.get',
                'period' => 100000000000000,
            ],
        ],

        // Sidebar items:
        [
            'text' => 'Dashboard',
            'route' => 'dashboard.index',
            'icon' => 'fas fa-tachometer-alt',
            'active' => ['dashboard*'],
        ],

        ['header' => 'ACCESOS RÁPIDOS'],
        [
            'text' => 'Registrar pedidos',
            'can' => 'pedidos.create',
            'icon_color' => 'blue',
            'route' => 'pedidos.create',
            'icon' => 'fas fa-cart-plus',
            'active' => ['registrar pedidos*'],
        ],
        [
            'text' => 'Registrar pagos',
            'can' => 'pagos.create',
            'icon_color' => 'green',
            'route' => 'pagos.create',
            'icon' => 'far fa-id-card',
            'active' => ['registrar pagos*'],
        ],
        [
            'text' => 'Perdonar curier',
            'route' => 'pedidosperdonarcurrier',
            'can' => 'pagos.perdonarcourier',
            'icon' => 'fas fa-check-circle',
            'active' => ['pagos perdonar courier'],
        ],
        [
            'text' => 'Llamados de atencion',
            'can' => 'access.llamados',
            'icon_color' => 'green',
            'route' => 'llamados.atencion',
            'icon' => 'far fa-id-card',
            'active' => ['llamados atencion*'],
        ],
        [
            'text' => 'Configuracion',
            'route' => 'settings.admin-settings',
            'can' => 'admin.configuration',
            'icon' => 'fas fa-cogs',
            'active' => ['pagos perdonar courier'],
        ],

        ['header' => 'MÓDULOS'],

        [
            'text' => 'Pedidos',
            'icon' => 'fas fa-truck',
            'can' => 'pedidos.modulo',
            'submenu' => [
                [
                    'text' => 'Bandeja de pedidos',
                    'route' => 'pedidos.index',
                    'can' => 'pedidos.index',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['pedidos'],
                ],
                [
                    'text' => 'Pedidos Por Atender',
                    'route'  => 'pedidos.estados.poratender',
                    'can' => 'pedidos.estados.poratender',
                    'icon'   => 'fas fa-check-circle dot_pedidos_atender_count',
                    'active' => ['estatus pedido'],
                ],
                [
                    'text' => 'Pedidos Atendidos',
                    'route'  => 'pedidos.estados.atendidos',
                    'can' => 'pedidos.estados.atendidos',
                    'icon'   => 'fas fa-check-circle dot_pedidos_atendidos_count',
                    'active' => ['estatus pedido'],
                ],
                [
                    'text' => 'Pedidos por cobrar',
                    'route' => 'pedidos.sinpagos',
                    'can' => 'pedidos.sinpagos',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['pedidos sin pagos'],
                ],
                [
                    'text' => 'Sobres por enviar',
                    'route' => 'sobres.porenviar',
                    'can' => 'pedidos.sobresporenviar',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['pedidos pagados'],
                ],
                /*[
                    'text' => 'Olva Tienda/Agente',
                    'route' => 'envios.olva.index',
                    'can' => 'envios.encargado.tienda_agente',
                    'icon' => 'fas fa-check-circle dot_encargado_tienda_agente_count',
                    'active' => ['enviados'],
                ],*/
                [
                  'text' => 'Bandejas de recojo',
                  'route' => 'pedidos.recojo',
                  'can' => 'pedidos.index',
                  'icon' => 'fas fa-check-circle',
                  'active' => ['pedidos de recojo'],
                ],
                [
                    'text' => 'Bandejas de anulaciones',
                    'route' => 'pedidos.anulaciones',
                    'can' => 'pedidos.index',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['pedidos anulaciones'],
                ],
            ],
        ],

        [
            'text' => 'Operaciones',
            'icon' => 'fas fa-print',
            'can' => 'operacion.modulo',
            'submenu' => [
                [
                    'text' => 'Bandeja de sobres',
                    'route' => 'operaciones.terminados',
                    'can' => 'operacion.terminados',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['pedidos terminados'],
                ],
                [
                    'text' => 'Bandeja de correcciones',
                    'route' => 'operaciones.correcciones',
                    'can' => 'operacion.correccion',
                    'icon' => 'fas fa-check-circle dot_correcciones_count',
                    'active' => [' correcciones'],
                ],
                [
                  'text' => 'Bandeja de recojo',
                  'route' => 'operaciones.recojos.index',
                  'can' => 'operacion.correccion',
                  'icon' => 'fas fa-share-square',
                  'active' => [' bandeja de recojo'],
                ],
                [
                    'text' => 'Pedidos por atender',
                    'route' => 'operaciones.poratender',
                    'can' => 'operacion.poratender',
                    'icon' => 'fas fa-check-circle dot_pedidos_atender_count',//'noti-pedidos-atender noti-side',
                    'active' => ['pedidos por atender'],
                ],
                [
                    'text' => 'Pedidos en atención',
                    'route'  => 'operaciones.enatencion',
                    'can' => 'operacion.enatencion',
                    'icon'   => 'fas fa-check-circle dot_pedidos_atencion_count',
                    'active' => ['pedidos en atención'],
                ],
                [
                    'text' => 'Pedidos listo para envio',
                    'route' => 'operaciones.atendidos',
                    'can' => 'operacion.atendidos',
                    'icon' => 'fas fa-check-circle dot_pedidos_atendidos_operacion_count',
                    'active' => ['pedidos atendidos'],
                ],
                [
                    'text' => 'En bancarización',
                    'route' => 'operaciones.bancarizacion',
                    'can' => 'operacion.bancarizacion',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['pedidos bancarizacion'],
                ],
                [
                    'text' => 'Jefe de Operaciones',
                    'route' => 'operaciones.entregados',
                    'can' => 'operacion.entregados',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['pedidos entregados'],
                ],
                [
                    'text' => 'Pendiente Anulación',
                    'route' => 'pedidos.estados.anulados',
                    'can' => 'pedidos.pendiente.anulacion',
                    'icon' => 'fas fa-check-circle dot_pedidos_pen_anulacion_count',
                    //'icon' => 'noti-pedidos-pen-anulacion noti-side',
                    'active' => ['Pedidos Pendiente'],
                ],

            ],
        ],
        [
            'text' => 'Courier',
            'icon' => 'fa fa-archive',
            'can' => 'courier.modulo',
            'submenu' => [
                [
                    'text' => 'Estado Sobres',
                    'route' => 'envios.estadosobres',
                    'can' => 'courier.estadosobres',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['envios estadosobres'],
                ],
                [
                    'text' => 'Recepción de sobres',
                    'route' => 'envios.porconfirmar',
                    'can' => 'courier.recepcionsobres',
                    'icon' => 'fas fa-check-circle dot_sobres_confirmar_recepcion_count',
                    'active' => ['envios'],
                ],
                [
                    'text' => 'Sobres con direccion',
                    'route' => 'envios.distribuirsobres',
                    'can' => 'courier.sobrescondireccion',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['envios con direccion'],
                ],
                [
                    'text' => 'Sobres sin direccion',
                    'route' => 'envios.sindireccion',
                    'can' => 'courier.sobressindireccion',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['envios sin direccion'],
                ],

                [
                    'text' => 'Sobres para reparto',
                    'route' => 'envios.parareparto',
                    'can' => 'courier.sobresparareparto',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['envios para reparto'],
                ],


                /*[
                    'text' => 'Sobres por recibir',
                    'route' => 'envios.porrecibir',
                    'can' => 'envios.porrecibir',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['envios'],
                ],*/
                /*[
                    'text' => 'Sobres en reparto',
                    'route' => 'envios.enreparto',
                    'can' => 'envios.index',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['envios'],
                ],*/
                /*[
                    'text' => 'Motorizado',
                    'route' => 'envios.motorizados.index',
                    'can' => 'operacion.confirmmotorizado.confirm',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['enviados'],
                ],*/
                /*
                [
                    'text' => 'Sobre entregados',
                    'route' => 'envios.entregados',
                    'can' => 'courier.sobresentregados',
                    'icon' => 'fas fa-check-circle',//dot_sobres_entregados_count
                    //'icon' => 'noti-sobres-entregados noti-side',
                    'active' => ['enviados'],
                ],*/
                [
                    'text' => 'Sobres en Ruta',
                    'route' => 'envios.rutaenvio',
                    'can' => 'courier.sobresenruta',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['envios'],
                ],
                [
                    'text' => 'Confirmar foto',
                    'route' => 'envios.motorizados.confirmar',
                    'can' => 'courier.confirmarfoto',
                    'icon' => 'fas fa-check-circle dot_contador_en_motorizados_confirmar_count',
                    'active' => ['enviados'],
                ],
                [
                    'text' => 'Seguimiento provincia',
                    'route' => 'envios.seguimientoprovincia',
                    'can' => 'envios.seguimientoprovincia',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['enviados'],
                ],
                /*[
                    'text' => 'Courier',
                    'route' => 'courierregistro',
                    'can' => 'envios.seguimientoprovincia',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['enviados'],
                ],
                [
                    'text' => 'Math rotulos',
                    'route' => 'envios.matchrotulos',
                    'can' => 'envios.seguimientoprovincia',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['enviados'],
                ]*/
            ],
        ],

        [
            'text' => 'Motorizado',
            'icon' => 'fa fa-motorcycle',
            'can' => 'motorizado.modulo',
            'submenu' => [
                [
                    'text' => 'Recepción motorizado',
                    'route' => 'envios.recepcionmotorizado',
                    'can' => 'motorizado.recepcion',
                    'icon' => 'fas fa-check-circle dot_sobres_confirmar_recepcion_motorizado_count',
                    'active' => ['envios recepcion motorizado'],
                ],
                [
                    'text' => 'Motorizado en ruta',
                    'route' => 'envios.motorizados.index',
                    'can' => 'motorizado.enruta',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['enviados'],
                ],
                [
                    'text' => 'Sobres por Devolver',
                    'route' => 'envios.devueltos',
                    'can' => 'motorizado.devuelto',
                    'icon' => 'fas fa-check-circle dot_contador_sobres_devueltos',
                    'active' => ['enviados'],
                ]
            ]
        ],
        [
            'text' => 'Pagos',
            'icon' => 'fas fa-cash-register',
            'can' => 'pagos.modulo',
            'submenu' => [
                [
                    'text' => 'Bandeja de Pagos',
                    'route' => 'pagos.index',
                    'can' => 'pagos.index',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['pagos'],
                ],
                /*[
                    'text' => 'Mis pagos',
                    'route' => 'pagos.mispagos',
                    'can' => 'pagos.mispagos',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['mis pagos'],
                ],
                [
                    'text' => 'Pagos incompletos',
                    'route' => 'pagos.pagosincompletos',
                    'can' => 'pagos.pagosincompletos',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['pagos incompletos'],
                ],*/
                [
                    'text' => 'Pagos observados',
                    'route' => 'pagos.pagosobservados',
                    'can' => 'pagos.pagosobservados',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['pagos observados'],
                ],
            ],
        ],
        [
            'text' => 'Administracion',
            'icon' => 'fa fa-comments-dollar',
            'can' => 'administracion.modulo',
            'submenu' => [
                [
                    'text' => 'Compr. Por revisar',
                    'route' => 'administracion.porrevisar',
                    'can' => 'administracion.porrevisar',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['pagos por revisar*'],
                ],
                [
                    'text' => 'Compr pendientes',
                    'route' => 'administracion.pendientes',
                    'can' => 'administracion.pendientes',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['pagos pendientes*'],
                ],
                [
                    'text' => 'Compr observados',
                    'route' => 'administracion.observados',
                    'can' => 'administracion.observados',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['pagos observados*'],
                ],
                /*[
                    'text' => 'Voucher con abono parcial',
                    'route'  => 'administracion.abonados',
                    'can' => 'administracion.porrevisar',
                    'icon'   => 'fas fa-check-circle',
                    'active' => ['pagos abonados*'],
                ],*/
                [
                    'text' => 'Abonados',
                    'route' => 'administracion.aprobados',
                    'can' => 'administracion.aprobados',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['pagos aprobados*'],
                ],
                [
                    'text' => 'Movimientos bancarios',
                    'route' => 'movimientos.index',
                    'can' => 'administracion.movimientos',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['movimientos bancarios*'],
                ],
            ],
        ],

        [
            'text' => 'Personas',
            'icon' => 'fas fa-users',
            'can' => 'personas.modulo',
            'submenu' => [

                [
                    'text' => 'Clientes',
                    'route' => 'clientes.index',
                    'can' => 'clientes.index',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['clientes'],
                ],
                [
                    'text' => 'Clientes Pretendidos',
                    'route' => 'clientes.pretendidos',
                    'can' => 'clientes.pretendidos',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['pretendidos'],
                ],
                [
                    'text' => 'Clientes Caidos',
                    'route' => 'clientes.recurrentes',
                    'can' => 'clientes.recurrentes',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['recurrentes'],
                ],
                [
                    'text' => 'Clientes Levantados',
                    'route' => 'clientes.levantados',
                    'can' => 'clientes.levantados',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['levantados'],
                ],
                [
                    'text' => 'Clientes Activo',
                    'route' => 'clientes.activos',
                    'can' => 'clientes.activos',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['activos'],
                ],
                [
                    'text' => 'Abandono Recientes',
                    'route' => 'clientes.abandonos.recientes',
                    'can' => 'clientes.abandonos.reciente',//
                    'icon' => 'fas fa-check-circle',
                    'active' => ['abandono recientes'],
                ],
                /*[
                    'text' => 'CASI ABANDONO',
                    'route' => 'clientes.abandonos.recientes.abandono',
                    'can' => 'clientes.abandonos.reciente.abandono',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['clientes'],
                ],*/
                [
                    'text' => 'Abandono',
                    'route' => 'clientes.abandonos',
                    'can' => 'clientes.abandonos',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['abandonos'],
                ],

                [
                    'text' => 'Clientes Nuevos',
                    'route' => 'clientes.nuevos',
                    'can' => 'clientes.nuevos',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['nuevos'],
                ],
                [
                    'text' => 'Recuperados Reciente',
                    'route' => 'clientes.recuperados.recientes',
                    'can' => 'clientes.recuperados.reciente',//
                    'icon' => 'fas fa-check-circle',
                    'active' => ['recuperado reciente'],
                ],
                [
                    'text' => 'Recuperados Abandono',
                    'route' => 'clientes.recuperados',
                    'can' => 'clientes.recuperados',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['recuperados'],
                ],
                [
                  'text' => 'Nulos',
                  'route' => 'clientes.nulos',
                  'can' => 'clientes.recuperados',
                  'icon' => 'fas fa-check-circle',
                  'active' => ['Nulos'],
                ],

                [
                    'text' => 'Base fría',
                    'route' => 'basefria',
                    'can' => 'base_fria.index',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['base fria'],
                ],
            ],
        ],

        [
            'text' => 'Reportes',
            'icon' => 'fas fa-chart-bar',
            'can' => 'reporte.modulo',
            'submenu' => [
                [
                    'text' => 'Reporte general',
                    'route' => 'reportes.index',
                    'can' => 'reportes.index',
                    'icon' => 'fas fa-check-circle',
                ],
                /*[
                    'text' => 'Reportes mis asesores',
                    'route'  => 'reportes.misasesores',
                    'can' => 'reportes.misasesores',
                    'icon'   => 'fas fa-check-circle',
                ],*/
                [
                    'text' => 'Reportes de operaciones',
                    'route' => 'reportes.operaciones',
                    'can' => 'reportes.operaciones',
                    'icon' => 'fas fa-check-circle',
                ],
                [
                    'text' => 'Analisis',
                    'route' => 'reportes.analisis',
                    'can' => 'reportes.operaciones',
                    'icon' => 'fas fa-check-circle',
                ],
            ],
        ],

        [
            'text' => 'Accesos y Permisos',
            'icon' => 'fas fa-user',
            'can' => 'configuracion.modulo',
            'submenu' => [
                [
                    'text' => 'Bandeja de Roles',
                    'route' => 'roles.index',
                    'can' => 'roles.index',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['roles*'],
                ],
                [
                    'text' => 'Usuarios',
                    'route' => 'users.index',
                    'can' => 'users.index',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['users'],
                ],
                /*[
                    'text' => 'Encargados',
                    'route' => 'users.encargados',
                    'can' => 'users.encargados',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['encargados'],
                ],*/
                [
                    'text' => 'Asesores',
                    'route' => 'users.asesores',
                    'can' => 'users.asesores',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['asesores'],
                ],
                /*[
                    'text' => 'Mis asesores',
                    'route' => 'users.misasesores',
                    'can' => 'users.misasesores',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['mis asesores'],
                ],*/
                [
                    'text' => 'Jefes de operaciones',
                    'route' => 'users.jefes',
                    'can' => 'users.jefes',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['jefes'],
                ],
                [
                    'text' => 'Operarios',
                    'route' => 'users.operarios',
                    'can' => 'users.operarios',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['operarios'],
                ],
                [
                    'text' => 'Mis operarios',
                    'route' => 'users.misoperarios',
                    'can' => 'users.misoperarios',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['mis operarios'],
                ],
                [
                    'text' => 'Mi personal',
                    'route' => 'users.mipersonal',
                    'can' => 'users.misoperarios',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['mi personal'],
                ],
                [
                    'text' => 'Llamadas y Cobranzas',
                    'route' => 'users.llamadas',
                    'can' => 'users.llamadas',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['mis llamadas'],
                ],
            ],
        ],
        [
            'text' => 'Planificacion',
            'icon' => 'fas fa-user',
            'can' => 'planificacion.modulo',
            'submenu' => [
                [
                    'text' => 'Calendario',
                    'route' => 'fullcalendarindex',
                    'can' => 'planificacion.calendario',
                    'icon' => 'fas fa-check-circle',
                    'active' => ['roles*'],
                ],
            ]
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => 'vendor/datatables/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@11',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'css/boot-dev.css?t=v1',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
        'bootstrap-select' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/bootstrap-select@1.13.18/dist/js/bootstrap-select.min.js',
                ],
                /*[
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/bootstrap-select@1.13.18/dist/js/i18n/defaults-*.min.js',
                ],*/
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/bootstrap-select@1.13.18/dist/css/bootstrap-select.min.css',
                ],
            ],
        ],
        'jqConfirm' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js?boot=1',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css?boot=1',
                ],
            ],
        ],
        'pnotify' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/pnotify/PNotify.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/pnotify/PNotifyConfirm.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/pnotify/PNotifyFontAwesome4.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/pnotify/PNotifyMobile.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/pnotify/PNotifyBootstrap4.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/pnotify/PNotify.css',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/pnotify/Material.css',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/pnotify/PNotifyBootstrap4.css',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/pnotify/PNotifyConfirm.css',
                ],
            ],
        ],
        'BootstrapColorpicker' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => false,
];
