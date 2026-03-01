<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Dominios raíz del ecosistema (landings)
    |--------------------------------------------------------------------------
    | Dos modos según cómo se accede:
    |
    | MODO SUBDOMINIO (producción):
    |   Host tiene subdominio → extraer tenant del subdominio.
    |   Ej: pepe.tu.menu → tenant subdomain='pepe'
    |
    | MODO PATH (local/desarrollo):
    |   Host ES el dominio raíz → extraer tenant del primer segmento de la URL.
    |   Ej: synticorex.test/pepe → tenant subdomain='pepe'
    |
    | El middleware IdentifyTenant detecta automáticamente cuál modo usar.
    */
    'central_domains' => [
        'synticorex.test', // Entorno local (Laragon)
        '127.0.0.1',       // php artisan serve / Herd local
        'localhost',        // Docker / nginx local
        'tu.menu',
        'menu.vip',
        'alto.aqui',
    ],

    /*
    |--------------------------------------------------------------------------
    | Dominios de gestión (marketing + panel)
    |--------------------------------------------------------------------------
    | En estos hosts no se resuelve tenant; son la app de ventas y el panel.
    | syntiweb.com = marketing | app.syntiweb.com / tablero.syntiweb.com = panel.
    */
    'admin_domains' => [
        'syntiweb.com',
        'app.syntiweb.com',
        'tablero.syntiweb.com',
        'app.syntiweb.test', // Panel en local (Laragon/Valet)
    ],

    /*
    |--------------------------------------------------------------------------
    | Dominio del panel (Login, Dashboard, Breeze)
    |--------------------------------------------------------------------------
    | Solo este host sirve las rutas de administración; evita duplicar nombres.
    | En local: app.syntiweb.test | En producción: app.syntiweb.com
    */
    'panel_domain' => env('PANEL_DOMAIN', 'app.syntiweb.test'),
];
