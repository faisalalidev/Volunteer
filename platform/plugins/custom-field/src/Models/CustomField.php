<?php

namespace Botble\CustomField\Models;

use Botble\Base\Models\BaseModel;
use Exception;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CustomField extends BaseModel
{
    public $timestamps = false;

    protected $table = 'custom_fields';

    protected $fillable = [
        'use_for',
        'use_for_id',
        'parent_id',
        'type',
        'slug',
        'value',
        'field_item_id',
    ];

    public function useCustomFields(): MorphTo
    {
        return $this->morphTo()->withDefault();
    }

    protected function resolvedValue(): Attribute
    {
        return Attribute::make(
            get: function () {
                $value = $this->value;

                if ($this->type === 'repeater') {
                    try {
                        $value = json_decode((string)$this->value, true);
                    } catch (Exception) {
                        $value = [];
                    }
                }

                return $value;
            },
        );
    }
}
