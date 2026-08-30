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
        Schema::create('wedding_tasks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('wedding_id')
                ->constrained('weddings')
                ->cascadeOnDelete();

            $table->string('task_name');

            $table->text('description')
                ->nullable();

            $table->date('due_date')
                ->nullable();

            $table->string('priority')
                ->default('medium');

            $table->string('status')
                ->default('pending');

            $table->timestamps();

            $table->index([
                'wedding_id',
                'status',
            ]);

            $table->index([
                'wedding_id',
                'due_date',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wedding_tasks');
    }
};