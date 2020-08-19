<?php

use App\LogType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogTypesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('log_types', function (Blueprint $table) {
      $table->id();
      $table->string('name', 255);
    });

    LogType::create(['name' => 'create']);
    LogType::create(['name' => 'update']);
    LogType::create(['name' => 'delete']);
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('log_types');
  }
}
