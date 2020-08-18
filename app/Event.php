<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
  public $timestamps = false;
  protected $fillable = ['start', 'end', 'title', 'color', 'description'];
}
