<?php

namespace Botble\Region;

use Illuminate\Support\Facades\Schema;
use Botble\PluginManagement\Abstracts\PluginOperationAbstract;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('regions');
        Schema::dropIfExists('regions_translations');
    }
}
