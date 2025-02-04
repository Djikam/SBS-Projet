<?php
namespace App\Events;

use App\Models\Intervention;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class InterventionUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $intervention;
    public $oldColor;

    public function __construct(Intervention $intervention, $oldColor)
    {
        $this->intervention = $intervention;
        $this->oldColor = $oldColor;
    }
}
