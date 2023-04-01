<?php

namespace Botble\CustomField\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CreateFieldGroupRequest extends Request
{
    public function rules(): array
    {
        return [
            'order' => 'integer|min:0|required',
            'rules' => 'json|required',
            'title' => 'required|max:255',
            'status' => ['required', Rule::in(BaseStatusEnum::values())],
        ];
    }
}
