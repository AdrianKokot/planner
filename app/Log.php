<?php

namespace App;

use App\User;
use App\Event;
use App\LogType;
use Carbon\Carbon;
use Exception;
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

    if (count($updatedData) > 0) {
      foreach($updatedData as $key => $value) {
        try {
          $value = Carbon::parse($value)->format('d.m.Y H:i');
          $event->{$key} = Carbon::parse($event->{$key})->format('d.m.Y H:i');
        } catch (Exception $e) {

        } finally {
          if($value != $event->{$key}) {
            $description = $description . '<div><strong>' . $key . '</strong> from "' . $event->{$key} . '" to "' . $value . '"</div>';
          }
        }
      }
      if (strlen($description) > 0) {
        $description = "Changed:" . $description;
      }
    }

    Log::create([
      'event_id' => $event->id,
      'user_id' => $user->id,
      'log_type_id' => $logType->id,
      'title' => "$user->name <strong>$declinedName</strong> event <strong>$event->title</strong> ($event->id)",
      'description' => $description
    ]);
  }
}
