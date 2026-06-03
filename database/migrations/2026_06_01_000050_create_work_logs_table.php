<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('work_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->date('date');
            $table->time('started_at')->nullable();
            $table->time('ended_at')->nullable();
            $table->decimal('hours', 5, 2)->nullable();
            $table->text('description')->nullable();
            $table->string('work_type')->nullable();
            $table->foreignId('ticket_id')->nullable()->constrained('tickets');
            $table->foreignId('project_id')->nullable()->constrained('projects');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('work_logs');
    }
};
