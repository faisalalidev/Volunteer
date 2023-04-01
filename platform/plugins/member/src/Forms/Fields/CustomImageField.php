<?php

namespace Botble\Member\Forms\Fields;

use Illuminate\Support\Arr;
use Kris\LaravelFormBuilder\Fields\FormField;

class CustomImageField extends FormField
{
    protected function getTemplate(): string
    {
        return 'plugins/member::forms.fields.custom-image';
    }

    public function render(array $options = [], $showLabel = true, $showField = true, $showError = true): string
    {
        $options['attr'] = Arr::set($options['attr'], 'class', Arr::get($options['attr'], 'class') . 'form-control');

        return parent::render($options, $showLabel, $showField, $showError);
    }
}
