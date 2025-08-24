<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agent_profiles', function (Blueprint $table) {
            $table->string('id_document_path')->nullable()->after('completed_transactions');
            $table->string('business_document_path')->nullable()->after('id_document_path');
            $table->text('rejection_reason')->nullable()->after('business_document_path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agent_profiles', function (Blueprint $table) {
            $table->dropColumn(['id_document_path', 'business_document_path', 'rejection_reason']);
        });
    }
};