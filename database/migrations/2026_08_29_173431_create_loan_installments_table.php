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
        Schema::create('loan_installments', function (Blueprint $table) {

            $table->id();

            $table->foreignId('loan_id')
                ->constrained('loans')
                ->cascadeOnDelete();

            $table->unsignedInteger('installment_number');

            $table->date('due_date');

            $table->decimal('amount_due', 12, 2);

            $table->decimal('amount_paid', 12, 2)
                ->default(0);

            $table->string('status', 30)
                ->default('upcoming');

            $table->timestamp('paid_at')
                ->nullable();

            $table->timestamps();

            $table->unique([
                'loan_id',
                'installment_number',
            ]);

            $table->index([
                'loan_id',
                'due_date',
            ]);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_installments');
    }
};