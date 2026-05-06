<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('sales');
            $table->boolean('activity')->default(false);
            $table->string('activity_name')->nullable();
            $table->integer('rainfall_mm')->default(0);
            $table->string('rain_level')->default('Low');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
