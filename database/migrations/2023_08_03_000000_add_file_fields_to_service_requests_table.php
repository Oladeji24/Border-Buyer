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
        Schema::table('service_requests', function (Blueprint $table) {
            $table->json('product_images')->nullable()->after('deadline');
            $table->json('additional_documents')->nullable()->after('product_images');
            $table->text('inspection_report')->nullable()->after('additional_documents');
            $table->json('inspection_photos')->nullable()->after('inspection_report');
            $table->string('tracking_number')->nullable()->after('inspection_photos');
            $table->text('notes')->nullable()->after('tracking_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropColumn([
                'product_images',
                'additional_documents',
                'inspection_report',
                'inspection_photos',
                'tracking_number',
                'notes'
            ]);
        });
    }
};