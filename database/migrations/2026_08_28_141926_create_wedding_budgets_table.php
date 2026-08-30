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
        Schema::create('wedding_budgets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('wedding_id')
                ->constrained('weddings')
                ->cascadeOnDelete();

            $table->string('category');

            $table->decimal('planned_amount', 12, 2)
                ->default(0);

            $table->decimal('actual_amount', 12, 2)
                ->default(0);

            $table->text('notes')
                ->nullable();

            $table->timestamps();

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
        Schema::dropIfExists('wedding_budgets');
    }
};