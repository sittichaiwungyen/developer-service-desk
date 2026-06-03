<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('deploy_logs', function (Blueprint $table) {
            $table->id();
            $table->string('deploy_number')->unique();
            $table->string('system_name');
            $table->string('version')->nullable();
            $table->string('environment')->nullable();
            $table->timestamp('deployed_at')->nullable();
            $table->foreignId('deployed_by')->nullable()->constrained('users');
            $table->text('release_note')->nullable();
            $table->text('rollback_plan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('deploy_logs');
    }
};
