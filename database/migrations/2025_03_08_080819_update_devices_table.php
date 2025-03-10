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
            $table->string('brand')->nullable()->change();
            $table->dropColumn('serial_number');
            $table->dropColumn('problem_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->string('brand')->nullable(false)->change();
            $table->string('serial_number')->nullable();
            $table->text('problem_description');
        });
    }
};
