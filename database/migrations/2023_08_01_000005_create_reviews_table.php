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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('reviewee_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('agent_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('service_request_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('marketplace_listing_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('transaction_id')->nullable()->constrained()->onDelete('set null');
            $table->tinyInteger('rating')->unsigned();
            $table->text('comment')->nullable();
            $table->boolean('is_public')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};