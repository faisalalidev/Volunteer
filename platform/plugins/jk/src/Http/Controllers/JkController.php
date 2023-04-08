<?php

namespace Botble\Jk\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Jk\Http\Requests\JkRequest;
use Botble\Jk\Repositories\Interfaces\JkInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Jk\Tables\JkTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Jk\Forms\JkForm;
use Botble\Base\Forms\FormBuilder;

class JkController extends BaseController
{
    protected JkInterface $jkRepository;

    public function __construct(JkInterface $jkRepository)
    {
        $this->jkRepository = $jkRepository;
    }

    public function index(JkTable $table)
    {
        page_title()->setTitle(trans('plugins/jk::jk.name'));

        return $table->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/jk::jk.create'));

        return $formBuilder->create(JkForm::class)->renderForm();
    }

    public function store(JkRequest $request, BaseHttpResponse $response)
    {
        $jk = $this->jkRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(JK_MODULE_SCREEN_NAME, $request, $jk));

        return $response
            ->setPreviousUrl(route('jk.index'))
            ->setNextUrl(route('jk.edit', $jk->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int|string $id, FormBuilder $formBuilder, Request $request)
    {
        $jk = $this->jkRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $jk));

        page_title()->setTitle(trans('core/base::forms.edit_item', ['name' => $jk->name]));

        return $formBuilder->create(JkForm::class, ['model' => $jk])->renderForm();
    }

    public function update(int|string $id, JkRequest $request, BaseHttpResponse $response)
    {
        $jk = $this->jkRepository->findOrFail($id);

        $jk->fill($request->input());

        $jk = $this->jkRepository->createOrUpdate($jk);

        event(new UpdatedContentEvent(JK_MODULE_SCREEN_NAME, $request, $jk));

        return $response
            ->setPreviousUrl(route('jk.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $jk = $this->jkRepository->findOrFail($id);

            $this->jkRepository->delete($jk);

            event(new DeletedContentEvent(JK_MODULE_SCREEN_NAME, $request, $jk));

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
            $jk = $this->jkRepository->findOrFail($id);
            $this->jkRepository->delete($jk);
            event(new DeletedContentEvent(JK_MODULE_SCREEN_NAME, $request, $jk));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
