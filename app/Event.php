<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
  public $timestamps = false;
  protected $fillable = ['start', 'end', 'title', 'color', 'description', 'user_id'];

  public function user()
  {
    return $this->belongsTo('App\User');
  }

  public function expenses() {
    return $this->hasMany('App\Transfer');
  }
}
