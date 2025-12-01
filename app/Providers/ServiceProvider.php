<?php

namespace App\Providers;

use App\Contacts\ImportContact;
use App\Services\ImportService;
use Illuminate\Support\ServiceProvider as SupportServiceProvider;

class ServiceProvider extends SupportServiceProvider
{
    public array $singletons = [
        ImportContact::class => ImportService::class
    ];
}
