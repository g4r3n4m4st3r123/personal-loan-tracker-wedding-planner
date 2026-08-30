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
        // carry_over is already included in the salary_periods
        // table creation migration.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // carry_over is part of the base salary_periods schema.
    }
};