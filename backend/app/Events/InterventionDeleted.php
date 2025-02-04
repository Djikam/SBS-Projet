<?php

namespace App\Events;
namespace App\Events;

use App\Models\Intervention;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class InterventionDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $intervention;

    public function __construct(Intervention $intervention)
    {
        $this->intervention = $intervention;
    }
}
