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
        Schema::create('wedding_vendors', function (Blueprint $table) {
            $table->id();

            $table->foreignId('wedding_id')
                ->constrained('weddings')
                ->cascadeOnDelete();

            $table->string('vendor_name');

            $table->string('service_type');

            $table->string('contact_person')
                ->nullable();

            $table->string('phone')
                ->nullable();

            $table->string('email')
                ->nullable();

            $table->string('address')
                ->nullable();

            $table->decimal('agreed_amount', 12, 2)
                ->default(0);

            $table->decimal('amount_paid', 12, 2)
                ->default(0);

            $table->string('payment_status')
                ->default('unpaid');

            $table->date('booking_date')
                ->nullable();

            $table->date('service_date')
                ->nullable();

            $table->text('notes')
                ->nullable();

            $table->timestamps();

            $table->index([
                'wedding_id',
                'service_type',
            ]);

            $table->index([
                'wedding_id',
                'payment_status',
            ]);

            $table->index([
                'wedding_id',
                'service_date',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wedding_vendors');
    }
};