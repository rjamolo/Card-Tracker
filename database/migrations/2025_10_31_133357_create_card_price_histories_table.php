<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('card_price_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_price_id')->constrained()->onDelete('cascade');
            $table->integer('old_price')->nullable();
            $table->integer('new_price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_price_histories');
    }
};
