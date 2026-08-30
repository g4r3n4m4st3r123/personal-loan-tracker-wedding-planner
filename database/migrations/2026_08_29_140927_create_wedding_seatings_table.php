<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wedding_seatings', function (Blueprint $table) {

            $table->id();

            $table->foreignId('wedding_id')
                ->constrained('weddings')
                ->cascadeOnDelete();

            $table->foreignId('wedding_table_id')
                ->constrained('wedding_tables')
                ->cascadeOnDelete();

            $table->foreignId('wedding_guest_id')
                ->constrained('wedding_guests')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique('wedding_guest_id');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wedding_seatings');
    }
};