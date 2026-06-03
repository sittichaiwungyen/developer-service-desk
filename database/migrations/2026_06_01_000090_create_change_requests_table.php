<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('change_requests', function (Blueprint $table) {
            $table->id();
            $table->string('cr_number')->unique();
            $table->foreignId('requester_id')->nullable()->constrained('users');
            $table->text('description')->nullable();
            $table->text('reason')->nullable();
            $table->text('impact')->nullable();
            $table->text('risk_assessment')->nullable();
            $table->enum('approval_status', ['draft','submitted','approved','rejected','implemented','closed'])->default('draft');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('change_requests');
    }
};
