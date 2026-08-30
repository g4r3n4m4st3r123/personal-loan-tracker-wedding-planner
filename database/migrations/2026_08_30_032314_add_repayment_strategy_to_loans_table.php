<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->string('repayment_strategy', 50)
                ->default('standard')
                ->after('monthly_payment');

            $table->decimal('planned_extra_payment', 12, 2)
                ->default(0)
                ->after('repayment_strategy');

            $table->decimal('balloon_payment', 12, 2)
                ->default(0)
                ->after('planned_extra_payment');
        });
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn([
                'repayment_strategy',
                'planned_extra_payment',
                'balloon_payment',
            ]);
        });
    }
};