<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Dominios raíz del ecosistema (landings)
    |--------------------------------------------------------------------------
    | Si el host termina en uno de estos, el subdominio es el slug del tenant.
    | Ej: pepe.tu.menu → slug: pepe | menu.vip → sin subdominio (no aplica).
    */
    'central_domains' => [
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
    ],
];
