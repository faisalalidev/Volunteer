<?php

namespace Botble\Department\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;

class Department extends BaseModel
{
    protected $table = 'departments';

    protected $fillable = [
        'name',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
}
