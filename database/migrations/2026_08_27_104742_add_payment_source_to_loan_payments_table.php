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
            $table->string('payment_source')
                ->nullable()
                ->after('payment_method');

            $table->index([
                'user_id',
                'payment_source',
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
            $table->dropIndex([
                'loan_payments_user_id_payment_source_payment_date_index',
            ]);

            $table->dropColumn('payment_source');
        });
    }
};