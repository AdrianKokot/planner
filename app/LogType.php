<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogType extends Model
{
  public $timestamps = false;
  protected $fillable = [
    'name'
  ];

  public function getDeclinedName()
  {
    return substr($this->name, -1) == 'e' ? $this->name . 'd' : $this->name . 'ed';
  }
}
