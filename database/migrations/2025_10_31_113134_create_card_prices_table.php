<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('card_prices', function (Blueprint $table) {
            $table->id();
            $table->string('card_name');
            $table->string('source_url')->unique();
            $table->integer('price')->nullable();
            $table->string('image_url')->nullable();
            $table->timestamp('collected_at')->nullable();
            $table->timestamps(); // adds created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_prices');
    }
};
