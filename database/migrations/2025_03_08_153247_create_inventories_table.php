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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable()->unique();
            $table->integer('quantity')->default(0);
            $table->decimal('unit_price', 10, 2)->default(0.00);
            $table->decimal('selling_price', 10, 2)->default(0.00);
            $table->text('description')->nullable();
            $table->string('status')->default('in_stock'); // in_stock, low_stock, out_of_stock
            $table->integer('reorder_point')->default(5);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
