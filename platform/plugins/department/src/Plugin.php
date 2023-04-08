<?php

namespace Botble\Department;

use Illuminate\Support\Facades\Schema;
use Botble\PluginManagement\Abstracts\PluginOperationAbstract;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('departments');
        Schema::dropIfExists('departments_translations');
    }
}
