<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('requester_email')->nullable()->after('requester_name');
            $table->string('requester_position')->nullable()->after('requester_email');
            $table->string('location')->nullable()->after('contact_phone');
            $table->string('issue_type')->nullable()->after('subcategory');
            $table->string('affected_system')->nullable()->after('issue_type');
            $table->string('asset_tag')->nullable()->after('affected_system');
            $table->string('ip_address')->nullable()->after('asset_tag');
            $table->string('impact_level')->nullable()->after('priority');
            $table->text('resolution')->nullable()->after('description');
            $table->text('root_cause')->nullable()->after('resolution');
            $table->boolean('follow_up_required')->default(false)->after('root_cause');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn([
                'requester_email',
                'requester_position',
                'location',
                'issue_type',
                'affected_system',
                'asset_tag',
                'ip_address',
                'impact_level',
                'resolution',
                'root_cause',
                'follow_up_required',
            ]);
        });
    }
};
