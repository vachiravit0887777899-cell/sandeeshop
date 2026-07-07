<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('box_item_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['owned', 'sold', 'shipped'])->default('owned');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_inventories');
    }
};