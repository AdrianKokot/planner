<?php

namespace App;

use App\User;
use App\Event;
use App\LogType;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
  public $timestamps = false;
  protected $fillable = [
    'log_at', 'event_id', 'user_id', 'log_type_id', 'title', 'description'
  ];

  public static function log(User $user, Event $event, $logTypeName = 'create', $updatedData = [])
  {
    $logType = LogType::where('name', $logTypeName)->first();
    $declinedName = $logType->getDeclinedName();

    $description = '';

    // TODO add details? From what value to what value it was changed?
    // if (count($updatedData) > 0) {
    //   $description .= ucfirst($declinedName) . " " . join(', ', array_keys($updatedData));
    // }

    Log::create([
      'event_id' => $event->id,
      'user_id' => $user->id,
      'log_type_id' => $logType->id,
      'title' => "$user->name <strong>$declinedName</strong> event <strong>$event->title</strong> ($event->id)",
      'description' => $description
    ]);
  }
}
