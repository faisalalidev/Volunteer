<?php

namespace Botble\CustomField\Models;

use Botble\Base\Models\BaseModel;
use Botble\CustomField\Repositories\Interfaces\CustomFieldInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FieldItem extends BaseModel
{
    public $timestamps = false;

    protected $table = 'field_items';

    protected $fillable = [
        'field_group_id',
        'parent_id',
        'order',
        'title',
        'slug',
        'type',
        'instructions',
        'options',
    ];

    protected static function boot()
    {
        parent::boot();

        self::deleting(function (FieldItem $fieldItem) {
            app(CustomFieldInterface::class)->deleteBy(['field_item_id' => $fieldItem->id]);
        });
    }

    public function fieldGroup(): BelongsTo
    {
        return $this->belongsTo(FieldGroup::class, 'field_group_id')->withDefault();
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(FieldItem::class, 'parent_id')->withDefault();
    }

    public function child(): HasMany
    {
        return $this->hasMany(FieldItem::class, 'parent_id');
    }
}
