<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
  public $timestamps = false;
  protected $fillable = [
    'name', 'created_at', 'amount', 'transfer_type_id', 'transfer_category_id', 'event_id', 'user_id'
  ];

  public function transferType()
  {
    return $this->belongsTo('App\TransferType');
  }

  public function transferCategory()
  {
    return $this->belongsTo('App\TransferCategory');
  }

  public function event()
  {
    return $this->belongsTo('App\Event');
  }

  public function user()
  {
    return $this->belongsTo('App\User');
  }
}
