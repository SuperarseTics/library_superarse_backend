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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->index()->unique();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('book_id')->constrained();
            $table->timestamp('booking_date');
            $table->timestamp('delivery_date')->default(null)->nullable();
            $table->timestamp('giveback_date')->default(null)->nullable();
            $table->timestamp('last_giveback_date');
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
