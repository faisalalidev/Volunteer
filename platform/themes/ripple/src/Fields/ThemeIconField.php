<?php

namespace Theme\Ripple\Fields;

use Assets;
use Kris\LaravelFormBuilder\Fields\FormField;
use Theme;

class ThemeIconField extends FormField
{
    protected function getTemplate(): string
    {
        Assets::addScriptsDirectly(Theme::asset()->url('js/icons-field.js'));

        return Theme::getThemeNamespace() . '::partials.fields.icons-field';
    }
}
