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
        Schema::create('salary_periods', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->decimal('salary_amount', 12, 2);

            $table->decimal('carry_over', 12, 2)
                ->default(0);

            $table->date('period_start');

            $table->date('period_end');

            $table->date('salary_date');

            $table->string('salary_type', 50);

            $table->string('status', 50)
                ->default('current');

            $table->text('notes')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_periods');
    }
};