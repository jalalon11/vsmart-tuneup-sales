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
        Schema::table('devices', function (Blueprint $table) {
            // Add model_id column
            $table->foreignId('model_id')->nullable()->constrained('device_models')->nullOnDelete();
            
            // Make brand and model nullable since they can be derived from model_id
            $table->string('brand')->nullable()->change();
            $table->string('model')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            // Remove foreign key and column
            $table->dropForeign(['model_id']);
            $table->dropColumn('model_id');
            
            // Make brand and model required again
            $table->string('brand')->nullable(false)->change();
            $table->string('model')->nullable(false)->change();
        });
    }
};
