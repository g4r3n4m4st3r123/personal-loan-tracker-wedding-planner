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
        Schema::create('wedding_timeline_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('wedding_id')
                ->constrained('weddings')
                ->cascadeOnDelete();

            $table->string('title');

            $table->date('event_date');

            $table->time('start_time')
                ->nullable();

            $table->time('end_time')
                ->nullable();

            $table->string('location')
                ->nullable();

            $table->string('category')
                ->default('general');

            $table->string('status')
                ->default('planned');

            $table->string('priority')
                ->default('medium');

            $table->text('description')
                ->nullable();

            $table->timestamps();

            $table->index([
                'wedding_id',
                'event_date',
            ]);

            $table->index([
                'wedding_id',
                'status',
            ]);

            $table->index([
                'wedding_id',
                'category',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wedding_timeline_items');
    }
};  