<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransfersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('transfers', function (Blueprint $table) {
      $table->id();
      $table->string('name', 255);
      $table->timestamp('created_at');
      $table->decimal('amount');
      $table->bigInteger('transfer_type_id')->nullable();
      $table->bigInteger('transfer_category_id')->nullable();
      $table->bigInteger('event_id')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('transfers');
  }
}
