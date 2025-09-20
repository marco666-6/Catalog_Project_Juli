<?php

// database/migrations/2024_01_20_000001_create_contacts_table.php
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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id('contact_id');
            $table->string('company_name')->default('PT. Batam General Supplier');
            $table->text('address');
            $table->string('phone_primary');
            $table->string('phone_secondary')->nullable();
            $table->string('email_primary');
            $table->string('email_secondary')->nullable();
            $table->string('whatsapp');
            $table->json('operational_hours'); // Store as JSON for flexibility
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};