<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('about_settings', function (Blueprint $table) {
            $table->id();
            $table->string('banner_label')->default('About Us');
            $table->string('hero_heading_line1')->default('Workshops');
            $table->string('hero_heading_line2')->default('that awesome!');
            $table->text('hero_paragraph')->nullable();
            $table->string('hero_image_path')->nullable();
            $table->string('hero_cta_label')->default('get a quote');
            $table->string('hero_cta_href')->nullable();
            $table->text('overview_paragraph1')->nullable();
            $table->text('overview_paragraph2')->nullable();
            $table->text('overview_callout')->nullable();
            $table->string('mission_heading')->default('Our mission');
            $table->string('mission_intro')->nullable();
            $table->text('approach_intro1')->nullable();
            $table->text('approach_intro2')->nullable();
            $table->string('footer_cta_text')->nullable();
            $table->string('footer_cta_button_label')->default('get a quote');
            $table->string('footer_cta_button_href')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('about_settings');
    }
};
