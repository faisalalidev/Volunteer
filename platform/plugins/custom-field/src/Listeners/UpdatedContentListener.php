<?php

namespace Botble\CustomField\Listeners;

use Botble\Base\Events\UpdatedContentEvent;
use CustomField;
use Exception;

class UpdatedContentListener
{
    public function handle(UpdatedContentEvent $event): void
    {
        try {
            CustomField::saveCustomFields($event->request, $event->data);
        } catch (Exception $exception) {
            info($exception->getMessage());
        }
    }
}
