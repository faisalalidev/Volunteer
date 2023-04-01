<?php

namespace Botble\CustomField\Facades;

use Illuminate\Support\Facades\Facade;
use Botble\CustomField\Support\CustomFieldSupport;

/**
 * @see CustomFieldSupport
 */
class CustomFieldSupportFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return CustomFieldSupport::class;
    }
}
