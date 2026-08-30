<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('loan_name');
            $table->string('lender')->nullable();

            $table->decimal('principal_amount', 12, 2);

            $table->decimal('interest_rate', 5, 2)
                ->default(0);

            $table->enum('interest_type', [
                'none',
                'simple',
                'fixed'
            ])->default('none');

            $table->unsignedInteger('term_months')->nullable();

            $table->decimal('monthly_payment', 12, 2)
                ->nullable();

            $table->date('start_date');
            $table->date('due_date')->nullable();

            $table->enum('status', [
                'active',
                'completed',
                'overdue',
                'cancelled'
            ])->default('active');

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('due_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
