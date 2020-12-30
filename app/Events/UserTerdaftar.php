<?php

namespace PMW\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use PMW\User;

class UserTerdaftar
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var User
     */
    public $user;

    /**
     * @var array
     */
    public $hakAkses;

    /**
     * @var string
     */
    public $password;

    /**
     * Create a new event instance.
     *
     * @param User $user
     * @param array $hakAkses
     */
    public function __construct(User $user, array $hakAkses = [], $password)
    {
        $this->user = $user;
        $this->hakAkses = $hakAkses;
        $this->password = $password;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
    
}
