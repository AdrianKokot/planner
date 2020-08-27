<?php

use App\TransferCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferCategoriesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('transfer_categories', function (Blueprint $table) {
      $table->id();
      $table->string('name', 255);
      $table->string('color', 32);
    });

    TransferCategory::create(['name' => 'commute', 'color' => 'var(--indigo)']);
    TransferCategory::create(['name' => 'education', 'color' => 'var(--cyan)']);
    TransferCategory::create(['name' => 'shopping', 'color' => 'var(--blue)']);
    TransferCategory::create(['name' => 'dining out', 'color' => 'var(--orange)']);
    TransferCategory::create(['name' => 'income', 'color' => 'var(--primary)']);
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('transfer_categories');
  }
}
