<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->default('Greenland Business & Compliance');
            $table->string('meta_title')->default('Greenland Business & Compliance');
            $table->text('meta_description')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('primary_phone')->nullable();
            $table->string('primary_email')->nullable();
            $table->string('address')->nullable();
            $table->string('business_hours')->nullable();
            $table->text('footer_description')->nullable();
            $table->string('footer_cta_title')->nullable();
            $table->text('footer_cta_text')->nullable();
            $table->string('footer_cta_button_label')->nullable();
            $table->string('footer_cta_button_href')->nullable();
            $table->string('map_embed_url', 1000)->nullable();
            $table->string('copyright_text')->nullable();
            $table->string('office_image_path')->nullable();
            $table->string('company_presentation_file')->nullable();
            $table->string('how_we_work_video_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
