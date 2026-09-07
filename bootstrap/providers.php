<?php

use App\Providers\AppServiceProvider;
use App\Providers\ContactServiceProvider;
use App\Providers\FortifyServiceProvider;
use App\Providers\UpdateServiceProvider;

return [
    AppServiceProvider::class,
    ContactServiceProvider::class,
    FortifyServiceProvider::class,
    UpdateServiceProvider::class,
];
