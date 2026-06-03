<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('db_change_logs', function (Blueprint $table) {
            $table->id();
            $table->string('object_type');
            $table->string('object_name');
            $table->string('database_name')->nullable();
            $table->text('change_description')->nullable();
            $table->foreignId('changed_by')->nullable()->constrained('users');
            $table->timestamp('deployed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('db_change_logs');
    }
};
