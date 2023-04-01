<?php

namespace Botble\CustomField\Tables;

use BaseHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\CustomField\Repositories\Interfaces\FieldGroupInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class CustomFieldTable extends TableAbstract
{
    protected $hasActions = true;

    protected $hasFilter = true;

    protected $view = 'plugins/custom-field::list';

    public function __construct(
        DataTables $table,
        UrlGenerator $urlGenerator,
        FieldGroupInterface $fieldGroupRepository
    ) {
        parent::__construct($table, $urlGenerator);

        $this->repository = $fieldGroupRepository;

        if (! Auth::user()->hasAnyPermission(['custom-fields.edit', 'custom-fields.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('title', function ($item) {
                if (! Auth::user()->hasPermission('custom-fields.edit')) {
                    return BaseHelper::clean($item->title);
                }

                return Html::link(route('custom-fields.edit', $item->id), BaseHelper::clean($item->title));
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            })
            ->addColumn('operations', function ($item) {
                return $this->getOperations(
                    'custom-fields.edit',
                    'custom-fields.destroy',
                    $item,
                    Html::link(
                        route('custom-fields.export', ['id' => $item->id]),
                        Html::tag('i', '', ['class' => 'fa fa-download'])->toHtml(),
                        [
                            'class' => 'btn btn-icon btn-info btn-sm tip',
                            'title' => trans('plugins/custom-field::base.export'),
                        ],
                        null,
                        false
                    )->toHtml()
                );
            });

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this->repository->getModel()->select([
            'id',
            'title',
            'status',
            'order',
            'created_at',
        ]);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            'id' => [
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'title' => [
                'title' => trans('core/base::tables.name'),
                'class' => 'text-start',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status' => [
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
            ],
        ];
    }

    public function buttons(): array
    {
        $buttons = $this->addCreateButton(route('custom-fields.create'), 'custom-fields.create');

        $buttons['import-field-group'] = [
            'link' => '#',
            'text' => view('plugins/custom-field::_partials.import')->render(),
        ];

        return $buttons;
    }

    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('custom-fields.deletes'), 'custom-fields.destroy', parent::bulkActions());
    }

    public function getBulkChanges(): array
    {
        return [
            'title' => [
                'title' => trans('core/base::tables.name'),
                'type' => 'text',
                'validate' => 'required|max:120',
            ],
            'status' => [
                'title' => trans('core/base::tables.status'),
                'type' => 'customSelect',
                'choices' => BaseStatusEnum::labels(),
                'validate' => 'required|' . Rule::in(BaseStatusEnum::values()),
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type' => 'datePicker',
            ],
        ];
    }
}
