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
    'log_at', 'user_id', 'log_type_id', 'title', 'description', 'item_table', 'item_id'
  ];

  private static $parseToDate = ['created_at', 'updated_at', 'start', 'end'];
  private static $hide = ['password', 'user_id'];

  /**
   * Creates new log in database about activity
   *
   * @param User $user User that called activity
   * @param StdClass $item Model that was created / changed / deleted
   * @param string $itemType Name of model type
   * @param string $logTypeName Type of activity. Default 'create'. Accepts 'create', 'update', 'delete'.
   * @param array $data Optional. Data in model that was changed.
   * @return void
   */
  public static function log(User $user, $item, string $itemType, string $logTypeName = 'create', array $data = [])
  {
    $logType = LogType::where('name', $logTypeName)->first();
    $declinedName = $logType->getDeclinedName();

    $description = '';

    if (count($data) > 0) {
      if ($logTypeName == 'update') {

        foreach ($data as $key => $value) {

          if (in_array($key, Log::$parseToDate)) {
            $value = Carbon::parse($value)->format('d.m.Y H:i');
            $item->{$key} = Carbon::parse($item->{$key})->format('d.m.Y H:i');
          }

          if(in_array($key, Log::$hide)) {
            $description = $description . '<div><strong>' . $key . '</strong></div>';
            continue;
          }

          if(is_array($value) && $key == 'permissions') {
            $value = '<ul><li>'.implode('</li><li>', $value) .'</li></ul>';
            $item->{$key} = '<ul><li>'.implode('</li><li>', $item->{$key}->pluck('name')->all()) .'</li></ul>';
          }

          if ($value != $item->{$key}) {
            $description = $description . '<div><strong>' . $key . '</strong> from <em>' . $item->{$key} . '</em> to <em>' . $value . '</em></div>';
          }

        }

        if (strlen($description) > 0) {
          $description = "Changed:" . $description;
        }

      } else if ($logTypeName == 'create') {

        foreach ($data as $key => $value) {

          if (in_array($key, Log::$parseToDate)) {
            $value = Carbon::parse($value)->format('d.m.Y H:i');
          }

          if(in_array($key, Log::$hide)) {
            continue;
          }

          if(is_array($value) && $key == 'permissions') {
            $value = '<ul><li>'.implode('</li><li>', $value) .'</li></ul>';
          }

          $description = $description . '<div><strong>' . $key . '</strong>: <em>' . $value . '</em></div>';
        }

        if (strlen($description) > 0) {
          $description = "Created with data:" . $description;
        }

      }
    }

    $itemTitle = !empty($item->name) ? $item->name : $item->title;

    Log::create([
      'item_id' => $item->id,
      'item_table' => $itemType,
      'user_id' => $user->id,
      'log_type_id' => $logType->id,
      'title' => "$user->name <strong>$declinedName</strong> $itemType <strong>$itemTitle</strong> ($item->id)",
      'description' => $description
    ]);
  }
}
