<?php

use App\Enums\WineRegion;
use App\Enums\WineType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('wines', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('appellation');
      $table->string('slug')->unique();

      $table->string('domain')->nullable();
      $table->string('grape')->nullable();
      $table->string('country')->default('France');
      $table->enum('region', array_column(WineRegion::cases(), 'value'))->nullable();

      $table->smallInteger('vintage');
      $table->enum('wine_type', array_column(WineType::cases(), 'value'));
      $table->decimal('price', 8, 2)->nullable();
      $table->string('seller')->nullable();
      $table->date('purchase_date')->nullable();

      $table->decimal('rating', 4, 1)->nullable();
      $table->boolean('favorite')->default(false);
      $table->boolean('buy_again')->nullable();
      $table->boolean('is_opened')->default(false);

      $table->text('description')->nullable();
      $table->string('image_path')->nullable();
      $table->text('nose')->nullable();
      $table->text('palate')->nullable();
      $table->json('pairings')->nullable();

      $table->timestamps();

      $table->index('wine_type');
      $table->index('region');
      $table->index('favorite');
      $table->index('vintage');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('wines');
  }
};
