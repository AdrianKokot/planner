<?php

use App\TransferType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferTypesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('transfer_types', function (Blueprint $table) {
      $table->id();
      $table->string('name', 255);
    });

    TransferType::create(['name' => 'income']);
    TransferType::create(['name' => 'expense']);
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('transfer_types');
  }
}
