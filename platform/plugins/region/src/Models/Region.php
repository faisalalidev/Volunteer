<?php

namespace Botble\Region\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Jk\Models\Jk;

class Region extends BaseModel
{
    protected $table = 'regions';

    protected $fillable = [
        'name',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function jk()
    {
      return $this->hasMany(Jk::class);
    }
}
