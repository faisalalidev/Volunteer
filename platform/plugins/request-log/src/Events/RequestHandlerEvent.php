<?php

namespace Botble\RequestLog\Events;

use Botble\Base\Events\Event;
use Illuminate\Queue\SerializesModels;

class RequestHandlerEvent extends Event
{
    use SerializesModels;

    public int $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }
}
