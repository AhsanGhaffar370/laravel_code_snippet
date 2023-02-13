<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $notification;
    public $notification_count;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($notification, $notification_count)
    {
        $this->notification = $notification;
        $this->notification_count = $notification_count;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
      $channel_name = 'notification.send';
      return new Channel($channel_name);
    }
    
    public function broadcastWith() { // customize data (send only non private info)
      return [
          'id' => $this->notification->id,
          'user_id' => $this->notification->user_id,
          'message' => $this->notification->message,
          'notification_count' => $this->notification_count,
      ];
  }
  
  // public function broadcastAs() { // give your event a name
  //     return 'new-department-created';
  // }
}
