<?php

use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

protected $middlewareGroups = [
    'api' => [
        EnsureFrontendRequestsAreStateful::class, // Adiciona suporte ao Sanctum
        'throttle:api',
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],
];
