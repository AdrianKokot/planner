<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransferType extends Model
{
  public $timestamps = false;
  protected $fillable = ['name'];

  public function transfers()
  {
    return $this->hasMany('App\Transfer');
  }
}
