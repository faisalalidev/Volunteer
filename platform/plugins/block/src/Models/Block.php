<?php

namespace Botble\Block\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;

class Block extends BaseModel
{
    protected $table = 'blocks';

    protected $fillable = [
        'name',
        'description',
        'content',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'content' => SafeContent::class,
        'description' => SafeContent::class,
    ];
}
