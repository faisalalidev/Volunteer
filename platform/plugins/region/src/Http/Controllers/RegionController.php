<?php

namespace Botble\Region\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Region\Http\Requests\RegionRequest;
use Botble\Region\Repositories\Interfaces\RegionInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Region\Tables\RegionTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Region\Forms\RegionForm;
use Botble\Base\Forms\FormBuilder;

class RegionController extends BaseController
{
    protected RegionInterface $regionRepository;

    public function __construct(RegionInterface $regionRepository)
    {
        $this->regionRepository = $regionRepository;
    }

    public function index(RegionTable $table)
    {
        page_title()->setTitle(trans('plugins/region::region.name'));

        return $table->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/region::region.create'));

        return $formBuilder->create(RegionForm::class)->renderForm();
    }

    public function store(RegionRequest $request, BaseHttpResponse $response)
    {
        $region = $this->regionRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(REGION_MODULE_SCREEN_NAME, $request, $region));

        return $response
            ->setPreviousUrl(route('region.index'))
            ->setNextUrl(route('region.edit', $region->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int|string $id, FormBuilder $formBuilder, Request $request)
    {
        $region = $this->regionRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $region));

        page_title()->setTitle(trans('core/base::forms.edit_item', ['name' => $region->name]));

        return $formBuilder->create(RegionForm::class, ['model' => $region])->renderForm();
    }

    public function update(int|string $id, RegionRequest $request, BaseHttpResponse $response)
    {
        $region = $this->regionRepository->findOrFail($id);

        $region->fill($request->input());

        $region = $this->regionRepository->createOrUpdate($region);

        event(new UpdatedContentEvent(REGION_MODULE_SCREEN_NAME, $request, $region));

        return $response
            ->setPreviousUrl(route('region.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $region = $this->regionRepository->findOrFail($id);

            $this->regionRepository->delete($region);

            event(new DeletedContentEvent(REGION_MODULE_SCREEN_NAME, $request, $region));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $region = $this->regionRepository->findOrFail($id);
            $this->regionRepository->delete($region);
            event(new DeletedContentEvent(REGION_MODULE_SCREEN_NAME, $request, $region));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
