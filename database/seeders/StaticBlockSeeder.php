<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Block\Models\Block;
use Botble\Language\Models\LanguageMeta;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StaticBlockSeeder extends BaseSeeder
{
    public function run(): void
    {
        $faker = Factory::create();

        Block::truncate();
        DB::table('blocks_translations')->truncate();
        LanguageMeta::where('reference_type', Block::class)->delete();

        for ($i = 0; $i < 5; $i++) {
            $name = $faker->name();

            $block = Block::create([
                'name' => $name,
                'alias' => Str::slug($name),
                'description' => $faker->text(50),
                'content' => $faker->text(500),
            ]);

            DB::table('blocks_translations')->insert([
                'lang_code' => 'vi',
                'blocks_id' => $block->id,
                'name' => $block->name,
                'description' => $block->description,
                'content' => $block->content,
            ]);
        }
    }
}
