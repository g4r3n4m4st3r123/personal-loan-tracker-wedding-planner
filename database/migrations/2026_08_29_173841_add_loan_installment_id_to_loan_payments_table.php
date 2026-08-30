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
        Schema::table('loan_payments', function (Blueprint $table) {

            $table->foreignId('loan_installment_id')
                ->nullable()
                ->after('loan_id')
                ->constrained('loan_installments')
                ->nullOnDelete();

            $table->index([
                'loan_installment_id',
                'payment_date',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_payments', function (Blueprint $table) {

            $table->dropForeign([
                'loan_installment_id',
            ]);

            $table->dropIndex([
                'loan_installment_id',
                'payment_date',
            ]);

            $table->dropColumn('loan_installment_id');
        });
    }
};