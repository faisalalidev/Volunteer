<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('locations_translations', function (Blueprint $table) {
            $table->string('lang_code');
            $table->foreignId('locations_id');
            $table->string('name', 255)->nullable();

            $table->primary(['lang_code', 'locations_id'], 'locations_translations_primary');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');
        Schema::dropIfExists('locations_translations');
    }
};
