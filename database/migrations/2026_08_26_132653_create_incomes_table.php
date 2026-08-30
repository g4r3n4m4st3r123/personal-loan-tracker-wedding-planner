<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('income_type');
            $table->decimal('amount', 12, 2);
            $table->date('income_date');
            $table->text('description')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'income_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
