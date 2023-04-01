<?php

namespace Botble\Block\Repositories\Caches;

use Botble\Support\Repositories\Caches\CacheAbstractDecorator;
use Botble\Block\Repositories\Interfaces\BlockInterface;

class BlockCacheDecorator extends CacheAbstractDecorator implements BlockInterface
{
    public function createSlug(?string $name, int|string|null $id): string
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }
}
