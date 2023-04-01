<?php

namespace Database\Seeders;

use Botble\Base\Models\BaseModel;
use Botble\Setting\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'media_random_hash',
                'value' => md5(time()),
            ],
        ];

        Setting::whereIn('key', collect($settings)->pluck('key')->all())->delete();

        if (BaseModel::determineIfUsingUuidsForId()) {
            $settings = array_map(function ($item) {
                $item['id'] = BaseModel::newUniqueId();

                return $item;
            }, $settings);
        }

        Setting::insertOrIgnore($settings);
    }
}
