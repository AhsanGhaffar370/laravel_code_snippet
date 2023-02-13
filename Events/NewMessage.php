<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $message_details;
    public $message_count;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message_details, $message_count)
    {
        $this->message_details = $message_details;
        $this->message_count = $message_count;
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
      $channel_name = 'message.send';
      // return new PrivateChannel('channel-name');
      return new Channel($channel_name); // . $this->message_details use channel/privateChannel/PresenceChannel as per your need
    }
    
    public function broadcastWith() { // customize data (send only non private info)
      return [
          'id' => $this->message_details->id,
          'from_user_id' => $this->message_details->from_user_id,
          'from_user_name' => $this->message_details->fromUser->firstname . ' ' . $this->message_details->fromUser->lastname,
          'to_user_id' => $this->message_details->to_user_id,
          'to_user_name' => $this->message_details->toUser->firstname . ' ' . $this->message_details->toUser->lastname,
          'message' => $this->message_details->message,
          'attachment' => $this->message_details->attachment,
          'date' => $this->message_details->date,
          'time' => $this->message_details->time,
          'message_count' => $this->message_count,
          'attachment_file_path' => config('globals.STORAGE_PATH') . config('globals.MESSAGE_ATTACHMENT_FILE_PATH')
      ];
  }
  
  // public function broadcastAs() { // give your event a name
  //     return 'new-department-created';
  // }
}
