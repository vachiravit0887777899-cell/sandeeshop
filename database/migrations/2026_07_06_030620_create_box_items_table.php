<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('box_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('box_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('image')->nullable();
            $table->enum('rarity', ['common', 'rare', 'epic', 'legendary'])->default('common');
            $table->decimal('market_value', 12, 2)->default(0);
            $table->decimal('probability', 5, 2); // เช่น 25.50 หมายถึง 25.50%
            $table->integer('stock')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('box_items');
    }
};