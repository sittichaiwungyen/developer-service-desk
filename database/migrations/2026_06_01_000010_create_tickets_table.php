<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('ticket_number')->unique();
            $table->foreignId('department_id')->nullable()->constrained('departments');
            $table->foreignId('requester_id')->nullable()->constrained('users');
            $table->string('requester_name')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('subject');
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->string('subcategory')->nullable();
            $table->enum('priority', ['low','medium','high','critical'])->default('medium');
            $table->enum('status', ['new','assigned','in_progress','waiting_user','testing','done','closed','cancelled'])->default('new');
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->timestamp('sla_due_at')->nullable();
            $table->boolean('sla_breached')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};
