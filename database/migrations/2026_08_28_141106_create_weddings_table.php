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
        Schema::create('weddings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('wedding_name')
                ->default('Our Wedding');

            $table->string('partner_name')
                ->nullable();

            $table->date('wedding_date')
                ->nullable();

            $table->string('venue')
                ->nullable();

            $table->decimal('budget', 12, 2)
                ->default(0);

            $table->text('notes')
                ->nullable();

            $table->timestamps();

            $table->index([
                'user_id',
                'wedding_date',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weddings');
    }
};