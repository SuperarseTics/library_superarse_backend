<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default states
    |--------------------------------------------------------------------------
    |
    | This option controls the default cache store that will be used by the
    | framework. This connection is utilized if another isn't explicitly
    | specified when running a cache operation inside the application.
    |
    */

    'states' => [
        'reserve' => 'Reservado',
        'deliver' => 'Entregado',
        'giveback' => 'Devuelto',
        'notgiveback' => 'No Devuelto'
    ]
];
