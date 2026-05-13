<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_settings', function (Blueprint $table) {
            $table->id();
            $table->string('headline_line1')->default('Your Vision, Our Compliance.');
            $table->string('headline_line2')->default('Building Sustainable Business Foundations in Bangladesh');
            $table->text('paragraph')->nullable();
            $table->string('cta1_label')->default('Book a Consultation');
            $table->string('cta1_href')->default('/contact');
            $table->string('cta2_label')->default('Explore Our Services');
            $table->string('cta2_href')->default('/services');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_settings');
    }
};
