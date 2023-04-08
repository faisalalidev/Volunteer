<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('jks', function (Blueprint $table) {
            $table->integer('region_id');
        });


    }

    public function down(): void
    {
    }
};
