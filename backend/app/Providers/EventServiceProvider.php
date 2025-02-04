<?php

namespace App\Providers;
namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\InterventionCreated;
use App\Events\InterventionUpdated;
use App\Events\InterventionDeleted;
use App\Listeners\SubtractLeaveDays;
use App\Listeners\UpdateLeaveDays;
use App\Listeners\RestoreLeaveDays;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        InterventionCreated::class => [
            SubtractLeaveDays::class,
        ],
        InterventionUpdated::class => [
            UpdateLeaveDays::class,
        ],
        InterventionDeleted::class => [
            RestoreLeaveDays::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
