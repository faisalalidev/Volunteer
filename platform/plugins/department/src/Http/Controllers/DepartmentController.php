<?php

namespace Botble\Department\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Department\Http\Requests\DepartmentRequest;
use Botble\Department\Repositories\Interfaces\DepartmentInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Department\Tables\DepartmentTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Department\Forms\DepartmentForm;
use Botble\Base\Forms\FormBuilder;

class DepartmentController extends BaseController
{
    protected DepartmentInterface $departmentRepository;

    public function __construct(DepartmentInterface $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    public function index(DepartmentTable $table)
    {
        page_title()->setTitle(trans('plugins/department::department.name'));

        return $table->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/department::department.create'));

        return $formBuilder->create(DepartmentForm::class)->renderForm();
    }

    public function store(DepartmentRequest $request, BaseHttpResponse $response)
    {
        $department = $this->departmentRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(DEPARTMENT_MODULE_SCREEN_NAME, $request, $department));

        return $response
            ->setPreviousUrl(route('department.index'))
            ->setNextUrl(route('department.edit', $department->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int|string $id, FormBuilder $formBuilder, Request $request)
    {
        $department = $this->departmentRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $department));

        page_title()->setTitle(trans('core/base::forms.edit_item', ['name' => $department->name]));

        return $formBuilder->create(DepartmentForm::class, ['model' => $department])->renderForm();
    }

    public function update(int|string $id, DepartmentRequest $request, BaseHttpResponse $response)
    {
        $department = $this->departmentRepository->findOrFail($id);

        $department->fill($request->input());

        $department = $this->departmentRepository->createOrUpdate($department);

        event(new UpdatedContentEvent(DEPARTMENT_MODULE_SCREEN_NAME, $request, $department));

        return $response
            ->setPreviousUrl(route('department.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $department = $this->departmentRepository->findOrFail($id);

            $this->departmentRepository->delete($department);

            event(new DeletedContentEvent(DEPARTMENT_MODULE_SCREEN_NAME, $request, $department));

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
            $department = $this->departmentRepository->findOrFail($id);
            $this->departmentRepository->delete($department);
            event(new DeletedContentEvent(DEPARTMENT_MODULE_SCREEN_NAME, $request, $department));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
