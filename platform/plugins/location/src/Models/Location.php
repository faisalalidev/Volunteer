<?php

namespace Botble\Location\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Jk\Models\Jk;
use Botble\Region\Models\Region;

class Location extends BaseModel
{
    protected $table = 'locations';

    protected $fillable = [
        'name',
        'status',
        'jk_id',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function jk()
    {
        return $this->belongsTo(Jk::class,'jk_id');
    }
}
