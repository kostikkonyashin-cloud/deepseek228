<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();

            $table->integer('quantity');
            $table
                ->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table
                ->foreignId('product_id')
                ->constrained();
            $table
                ->foreignId('color_id')
                ->nullable()
                ->constrained();
            $table
                ->foreignId('size_id')
                ->nullable()
                ->constrained();
            $table
                ->foreignId('material_id')
                ->nullable()
                ->constrained();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
