<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('website_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('subTitle');
            $table->string('price');
            $table->string('description');
            $table->string('domain');
            $table->string('url');
            $table->string('productImageUrl');
            $table->string('logoUrl');
            $table->string('companyName');
            $table->string('companyPhoneNumber');
            $table->foreignUuid('post_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_details');
    }
};
