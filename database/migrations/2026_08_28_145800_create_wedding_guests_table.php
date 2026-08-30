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
        Schema::create('wedding_guests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('wedding_id')
                ->constrained('weddings')
                ->cascadeOnDelete();

            $table->string('name');

            $table->string('guest_type')
                ->default('guest');

            $table->string('rsvp_status')
                ->default('pending');

            $table->boolean('plus_one')
                ->default(false);

            $table->string('meal_preference')
                ->nullable();

            $table->string('phone')
                ->nullable();

            $table->string('email')
                ->nullable();

            $table->text('notes')
                ->nullable();

            $table->timestamps();

            $table->index([
                'wedding_id',
                'rsvp_status',
            ]);

            $table->index([
                'wedding_id',
                'guest_type',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wedding_guests');
    }
};