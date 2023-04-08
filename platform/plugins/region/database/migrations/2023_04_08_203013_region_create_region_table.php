<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('regions_translations', function (Blueprint $table) {
            $table->string('lang_code');
            $table->foreignId('regions_id');
            $table->string('name', 255)->nullable();

            $table->primary(['lang_code', 'regions_id'], 'regions_translations_primary');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regions');
        Schema::dropIfExists('regions_translations');
    }
};
