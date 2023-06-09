<?php

namespace Botble\CustomField\Actions;

use Illuminate\Support\Facades\Auth;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\CustomField\Repositories\Interfaces\FieldGroupInterface;

class UpdateCustomFieldAction extends AbstractAction
{
    public function __construct(protected FieldGroupInterface $fieldGroupRepository)
    {
    }

    public function run(int|string $id, array $data): array
    {
        $item = $this->fieldGroupRepository->findById($id);

        if (! $item) {
            return $this->error(trans('plugins/custom-field::base.item_not_existed'));
        }

        $data['updated_by'] = Auth::id();

        $result = $this->fieldGroupRepository->updateFieldGroup($item->id, $data);

        event(new UpdatedContentEvent(CUSTOM_FIELD_MODULE_SCREEN_NAME, request(), $result));

        if (! $result) {
            return $this->error();
        }

        return $this->success(null, [
            'id' => $result,
        ]);
    }
}
