<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JobDispatched
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $jobRunId;
    public $jobName;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($jobRunId, $jobName = "N/A")
    {
        $this->jobRunId = $jobRunId;
        $this->jobName = $jobName;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
