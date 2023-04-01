<?php

namespace Botble\CustomField\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\CustomField\Repositories\Interfaces\FieldItemInterface;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FieldGroup extends BaseModel
{
    protected $table = 'field_groups';

    protected $fillable = [
        'order',
        'rules',
        'title',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'title' => SafeContent::class,
    ];

    protected static function boot()
    {
        parent::boot();

        self::deleting(function (FieldGroup $fieldGroup) {
            app(FieldItemInterface::class)->deleteBy(['field_group_id' => $fieldGroup->id]);
        });
    }

    public function fieldItems(): HasMany
    {
        return $this->hasMany(FieldItem::class, 'field_group_id');
    }
}
