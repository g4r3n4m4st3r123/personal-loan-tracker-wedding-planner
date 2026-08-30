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
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            $table->string('currency')
                ->default('PHP');

            $table->string('currency_symbol')
                ->default('₱');

            $table->string('date_format')
                ->default('M d, Y');

            $table->string('week_starts_on')
                ->default('monday');

            $table->string('dashboard_view')
                ->default('overview');

            $table->boolean('show_wedding_dashboard')
                ->default(true);

            $table->boolean('show_finance_dashboard')
                ->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
};