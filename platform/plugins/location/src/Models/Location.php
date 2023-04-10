<?php

namespace Botble\Location\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;

class Location extends BaseModel
{
    protected $table = 'locations';

    protected $fillable = [
        'name',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
}
