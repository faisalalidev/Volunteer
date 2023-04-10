<?php

namespace Botble\Location;

use Illuminate\Support\Facades\Schema;
use Botble\PluginManagement\Abstracts\PluginOperationAbstract;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('locations');
        Schema::dropIfExists('locations_translations');
    }
}
