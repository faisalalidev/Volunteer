<?php

namespace Botble\Jk\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Region\Models\Region;

class Jk extends BaseModel
{
    protected $table = 'jks';

    protected $fillable = [
        'name',
        'region_id',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

//    protected $with = [
//        'region'
//    ];
    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
