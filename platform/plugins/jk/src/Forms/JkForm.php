<?php

namespace Botble\Jk\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Jk\Http\Requests\JkRequest;
use Botble\Jk\Models\Jk;
use Botble\Region\Models\Region;

class JkForm extends FormAbstract
{
    public function buildForm(): void
    {
        $region = Region::pluck('name','id');
        $this
            ->setupModel(new Jk)
            ->setValidatorClass(JkRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label' => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])->add('region_id', 'select', [
                'label' => 'Region',
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices' => $region->toArray(),
            ])
            ->add('status', 'customSelect', [
                'label' => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices' => BaseStatusEnum::labels(),
            ])
            ->setBreakFieldPoint('status');
    }
}
