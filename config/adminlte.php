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
    'title_postfix' => ' | sisFacturas',

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

    'logo' => 'sis<b>Facturas</b>',
    'logo_img' => 'vendor/adminlte/dist/img/logo_facturas.png',
    'logo_img_class' => 'brand-image-xl img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'sisFacturas',

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
    'sidebar_collapse' => false,
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
            'type'         => 'fullscreen-widget',
            'topnav_right' => true,
        ],

        [
            'type'         => 'navbar-notification',
            'id'           => 'my-notification',
            'icon'         => 'fas fa-bell',
            /* 'label'        => rand(0, 10) , */
            'label_color'  => 'danger',
            'route'          => 'notifications.index',
            'topnav_right' => true,
            'dropdown_mode'   => true,
            'dropdown_flabel' => 'Todas las notificaciones',
            'update_cfg'   => [
                'route' => 'notifications.get',
                'period' => 10,
            ],
        ],

        // Sidebar items:
        [
            'text'        => 'Dashboard',
            'route'         => 'dashboard.index',
            'icon'        => 'fas fa-tachometer-alt',
            'active' => ['dashboard*'],
        ],

        ['header' => 'ACCESOS RÁPIDOS'],
        [
            'text'       => 'Registrar pedidos',
            'can' => 'pedidos.create',
            'icon_color' => 'blue',
            'route'  => 'pedidos.create',
            'icon'    => 'fas fa-cart-plus',
            'active' => ['registrar pedidos*'],
        ],
        [
            'text'       => 'Registrar pagos',
            'can'        => 'pagos.create',
            'icon_color' => 'green',
            'route'  => 'pagos.create',
            'icon'    => 'far fa-id-card',
            'active' => ['registrar pagos*'],
        ],
        
        ['header' => 'MÓDULOS'],

        [
            'text'    => 'Pedidos',
            'icon'    => 'fa fa-file',
            'can' => 'pedidos.modulo',
            'submenu' => [                
                [
                    'text' => 'Bandeja de pedidos',
                    'route'  => 'pedidos.index',
                    'can' => 'pedidos.index',
                    'icon'   => 'fas fa-check-circle',
                    'active' => ['pedidos'],
                ],
                [
                    'text' => 'Mis pedidos',
                    'route'  => 'pedidos.mispedidos',
                    'can' => 'pedidos.mispedidos',
                    'icon'   => 'fas fa-check-circle',
                    'active' => ['mis pedidos'],
                ],
                [
                    'text' => 'Pedidos pagados',
                    'route'  => 'pedidos.pagados',
                    'can' => 'pedidos.pagados',
                    'icon'   => 'fas fa-check-circle',
                    'active' => ['pedidos pagados'],
                ],
                [
                    'text' => 'Pedidos por cobrar',
                    'route'  => 'pedidos.sinpagos',
                    'can' => 'pedidos.sinpagos',
                    'icon'   => 'fas fa-check-circle',
                    'active' => ['pedidos sin pagos'],
                ],
            ],
        ],

        [
            'text'    => 'Operaciones',
            'icon'    => 'fas fa-print',
            'can' => 'operacion.modulo',
            'submenu' => [
                [
                    'text' => 'Pedidos por atender',
                    'route'  => 'operaciones.poratender',
                    'can' => 'operacion.poratender',
                    'icon'   => 'fas fa-check-circle',
                    'active' => ['pedidos por atender'],
                ],
                [
                    'text' => 'Pedidos en atención',
                    'route'  => 'operaciones.enatencion',
                    'can' => 'operacion.enatencion',
                    'icon'   => 'fas fa-check-circle',
                    'active' => ['pedidos en atención'],
                ],
                [
                    'text' => 'Pedidos atendidos',
                    'route'  => 'operaciones.atendidos',
                    'can' => 'operacion.atendidos',
                    'icon'   => 'fas fa-check-circle',
                    'active' => ['pedidos atendidos'],
                ],
            ],
        ],

        [
            'text'    => 'Envios',
            'icon'    => 'fas fa-people-carry',
            'can'     => 'envios.modulo',
            'submenu' => [
                [
                    'text' => 'Bandeja de envios',
                    'route'  => 'envios.index',
                    'can' => 'envios.index',
                    'icon'   => 'fas fa-check-circle',
                    'active' => ['envios'],
                ],
                [
                    'text' => 'Entregados',
                    'route'  => 'envios.enviados',
                    'can' => 'envios.enviados',
                    'icon'   => 'fas fa-check-circle',
                    'active' => ['enviados'],
                ],
            ],
        ],

        [
            'text'    => 'Pagos',
            'icon'    => 'fas fa-cash-register',
            'can' => 'pagos.modulo',
            'submenu' => [
                [
                    'text' => 'Bandeja de Pagos',
                    'route'  => 'pagos.index',
                    'can' => 'pagos.index',
                    'icon'   => 'fas fa-check-circle',
                    'active' => ['pagos'],
                ],
                [
                    'text' => 'Mis pagos',
                    'route'  => 'pagos.mispagos',
                    'can' => 'pagos.mispagos',
                    'icon'   => 'fas fa-check-circle',
                    'active' => ['mis pagos'],
                ],
                [
                    'text' => 'Pagos incompletos',
                    'route'  => 'pagos.pagosincompletos',
                    'can' => 'pagos.pagosincompletos',
                    'icon'   => 'fas fa-check-circle',
                    'active' => ['pagos incompletos'],
                ],
                [
                    'text' => 'Pagos observados',
                    'route'  => 'pagos.pagosobservados',
                    'can' => 'pagos.pagosobservados',
                    'icon'   => 'fas fa-check-circle',
                    'active' => ['pagos observados'],
                ],
            ],
        ],

        [
            'text'    => 'Administracion',
            'icon'    => 'fa fa-comments-dollar',
            'can' => 'administracion.modulo',
            'submenu' => [
                [
                    'text' => 'Pagos por revisar',
                    'route'  => 'administracion.porrevisar',
                    'can' => 'administracion.porrevisar',
                    'icon'   => 'fas fa-check-circle',
                    'active' => ['pagos por revisar*'],
                ],
                [
                    'text' => 'Pagos observados',
                    'route'  => 'administracion.observados',
                    'can' => 'administracion.porrevisar',
                    'icon'   => 'fas fa-check-circle',
                    'active' => ['pagos observados*'],
                ],
                [
                    'text' => 'Pagos con abono parcial',
                    'route'  => 'administracion.abonados',
                    'can' => 'administracion.porrevisar',
                    'icon'   => 'fas fa-check-circle',
                    'active' => ['pagos abonados*'],
                ],
                [
                    'text' => 'Pagos aprobados',
                    'route'  => 'administracion.aprobados',
                    'can' => 'administracion.aprobados',
                    'icon'   => 'fas fa-check-circle',
                    'active' => ['pagos por revisar*'],
                ],
            ],
        ],

        [
            'text'    => 'Personas',
            'icon'    => 'fas fa-users',
            'can' => 'personas.modulo',
            'submenu' => [
                [
                    'text' => 'Clientes',
                    'route'  => 'clientes.index',
                    'can' => 'clientes.index',
                    'icon'   => 'fas fa-check-circle',
                    'active' => ['clientes'],
                ],
                [
                    'text' => 'Base fría',
                    'route'  => 'basefria',
                    'can' => 'base_fria.index',
                    'icon'   => 'fas fa-check-circle',
                    'active' => ['base fria'],
                ],
            ],
        ],
        
        [
            'text'    => 'Reportes',
            'icon'    => 'fas fa-chart-bar',
            'can'     => 'reporte.modulo',
            'submenu' => [
                [
                    'text' => 'Reporte general',
                    'route'  => 'reportes.index',
                    'can' => 'reportes.index',
                    'icon'   => 'fas fa-check-circle',
                ], 
                [
                    'text' => 'Reportes mis asesores',
                    'route'  => 'reportes.misasesores',
                    'can' => 'reportes.misasesores',
                    'icon'   => 'fas fa-check-circle',
                ],
                [
                    'text' => 'Reportes de operaciones',
                    'route'  => 'reportes.operaciones',
                    'can' => 'reportes.operaciones',
                    'icon'   => 'fas fa-check-circle',
                ],
            ],
        ],
        
        [
            'text'    => 'Accesos y permisos',
            'icon'    => 'fas fa-cogs',
            'can'     => 'configuracion.modulo',
            'submenu' => [
                [
                    'text' => 'Bandeja de Roles',
                    'route'  => 'roles.index',
                    'can' => 'roles.index',
                    'icon'   => 'fas fa-check-circle',
                    'active' => ['roles*'],
                ],
                [
                    'text' => 'Usuarios',
                    'route'  => 'users.index',
                    'can' => 'users.index',
                    'icon'   => 'fas fa-check-circle',
                    'active' => ['users'],
                ],
                [
                    'text' => 'Encargados',
                    'route'  => 'users.encargados',
                    'can' => 'users.encargados',
                    'icon'   => 'fas fa-check-circle',
                    'active' => ['encargados'],
                ],
                [
                    'text' => 'Asesores',
                    'route'  => 'users.asesores',
                    'can' => 'users.asesores',
                    'icon'   => 'fas fa-check-circle',
                    'active' => ['asesores'],
                ],
                [
                    'text' => 'Mis asesores',
                    'route'  => 'users.misasesores',
                    'can' => 'users.misasesores',
                    'icon'   => 'fas fa-check-circle',
                    'active' => ['mis asesores'],
                ],
                [
                    'text' => 'Jefes de operaciones',
                    'route'  => 'users.jefes',
                    'can' => 'users.jefes',
                    'icon'   => 'fas fa-check-circle',
                    'active' => ['jefes'],
                ],
                [
                    'text' => 'Operarios',
                    'route'  => 'users.operarios',
                    'can' => 'users.operarios',
                    'icon'   => 'fas fa-check-circle',
                    'active' => ['operarios'],
                ],
                [
                    'text' => 'Mis operarios',
                    'route'  => 'users.misoperarios',
                    'can' => 'users.misoperarios',
                    'icon'   => 'fas fa-check-circle',
                    'active' => ['mis operarios'],
                ],
            ],
        ],        
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
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
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
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
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
