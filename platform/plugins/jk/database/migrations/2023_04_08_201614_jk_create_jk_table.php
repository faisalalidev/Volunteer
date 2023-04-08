<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('jks', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('jks_translations', function (Blueprint $table) {
            $table->string('lang_code');
            $table->foreignId('jks_id');
            $table->string('name', 255)->nullable();

            $table->primary(['lang_code', 'jks_id'], 'jks_translations_primary');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jks');
        Schema::dropIfExists('jks_translations');
    }
};
