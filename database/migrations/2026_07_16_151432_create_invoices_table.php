<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('client_name');
            $table->string('client_email');
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('IDR');
            $table->text('description')->nullable();
            $table->enum('status', ['draft', 'sent', 'paid', 'overdue'])->default('draft');
            $table->date('due_date');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('last_reminder_at')->nullable();
            $table->integer('reminder_count')->default(0);
            $table->boolean('reminder_enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
