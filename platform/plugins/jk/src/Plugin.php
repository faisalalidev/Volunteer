<?php

namespace Botble\Jk;

use Illuminate\Support\Facades\Schema;
use Botble\PluginManagement\Abstracts\PluginOperationAbstract;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('jks');
        Schema::dropIfExists('jks_translations');
    }
}
