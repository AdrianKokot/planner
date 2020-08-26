<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransferCategory extends Model
{
  public $timestamps = false;
  protected $fillable = ['name', 'color'];

  public function transfers()
  {
    return $this->hasMany('App\Transfer');
  }
}
