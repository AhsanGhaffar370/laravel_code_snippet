<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\{DB,Session, Hash,URL,Storage,Validator,Auth};
use App\Notification;
use Pusher\Pusher;
use App\Events\NewNotification;
/*
|--------------------------------------------------------------------------
| Api Responser Trait
|--------------------------------------------------------------------------
|
| This trait will be used for uploading images.
|
| uploadedFile =    Image file
| folder =          Directory path
| disk =            Public
| filename =        Image name
*/

trait NotificationTrait
{
    public function notify_user($user_id, $message, $url)
    {
      $notification = Notification::create([
        'user_id' => $user_id,
        'message' => $message,
        'url' => $url,
      ]);

      // $notification_count = Notification::where('user_id', $user_id)->where('seen', 0)->count();

      // event(new NewNotification($notification, $notification_count));

      return $notification;
    }
}