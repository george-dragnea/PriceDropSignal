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
        Schema::create('price_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_url_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('price_cents');
            $table->timestamp('checked_at');

            $table->index(['product_url_id', 'checked_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_checks');
    }
};
