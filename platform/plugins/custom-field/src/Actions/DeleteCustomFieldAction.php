<?php

namespace Botble\CustomField\Actions;

use Botble\Base\Events\DeletedContentEvent;
use Botble\CustomField\Repositories\Interfaces\FieldGroupInterface;

class DeleteCustomFieldAction extends AbstractAction
{
    public function __construct(protected FieldGroupInterface $fieldGroupRepository)
    {
    }

    public function run(int|string $id): array
    {
        $fieldGroup = $this->fieldGroupRepository->findOrFail($id);
        $result = $this->fieldGroupRepository->delete($fieldGroup);

        event(new DeletedContentEvent(CUSTOM_FIELD_MODULE_SCREEN_NAME, request(), $fieldGroup));

        if (! $result) {
            return $this->error();
        }

        return $this->success(null, [
            'id' => $result,
        ]);
    }
}
