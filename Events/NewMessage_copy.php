<?php

namespace App\Events;

use App\Models\Message;
use App\Models\User;
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
      /**
     * User that sent the message
     *
     * @var User
     */
    public $to;
    public $from;

    /**
     * Message details
     *
     * @var Message
     */
    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $to, Message $message,User $from)
    {
        $this->to = $to;
        $this->message = $message;
        $this->from = $from;
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
      // $channel_name = 'message.send';
      return new PrivateChannel('message.send');
      // return new Channel($channel_name); // . $this->message_details use channel/privateChannel/PresenceChannel as per your need
    }
    
  //   public function broadcastWith() { // customize data (send only non private info)
  //     return [
  //         'id' => $this->message_details->id,
  //         'from_user_id' => $this->message_details->from_user_id,
  //         'from_user_name' => $this->message_details->fromUser->userDetail->firstname . ' ' . $this->message_details->fromUser->userDetail->lastname,
  //         'to_user_id' => $this->message_details->to_user_id,
  //         'to_user_name' => isset($this->message_details->group_id) ? null : ($this->message_details->toUser->userDetail->firstname . ' ' . $this->message_details->toUser->userDetail->lastname),
  //         'message' => $this->message_details->message,
  //         'attachment' => $this->message_details->attachment,
  //         'date' => $this->message_details->date,
  //         'time' => $this->message_details->time,
  //         'attachment_file_path' => config('globals.STORAGE_PATH') . config('globals.MESSAGE_ATTACHMENT_FILE_PATH')
  //     ];
  // }
  
  // public function broadcastAs() { // give your event a name
  //     return 'new-department-created';
  // }
}
