<?php

namespace Botble\Member\Tables;

use BaseHelper;
use Botble\Member\Repositories\Interfaces\MemberInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class MemberTable extends TableAbstract
{
    protected $hasActions = true;

    protected $hasFilter = true;

    public function __construct(DataTables $table, UrlGenerator $urlGenerator, MemberInterface $memberRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $memberRepository;

        if (! Auth::user()->hasAnyPermission(['member.edit', 'member.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('avatar_id', function ($item) {
                return Html::tag('img', '', ['src' => $item->avatar_thumb_url, 'alt' => $item->name, 'width' => 50]);
            })
            ->editColumn('first_name', function ($item) {
                if (! Auth::user()->hasPermission('member.edit')) {
                    return BaseHelper::clean($item->name);
                }

                return Html::link(route('member.edit', $item->id), BaseHelper::clean($item->name));
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->addColumn('operations', function ($item) {
                return $this->getOperations('member.edit', 'member.destroy', $item);
            });

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this->repository->getModel()->select([
            'id',
            'avatar_id',
            'first_name',
            'last_name',
            'email',
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
            'avatar_id' => [
                'title' => trans('plugins/member::member.avatar'),
                'width' => '70px',
            ],
            'first_name' => [
                'title' => trans('core/base::tables.name'),
                'class' => 'text-start',
            ],
            'email' => [
                'title' => trans('core/base::tables.email'),
                'class' => 'text-start',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
        ];
    }

    public function buttons(): array
    {
        return $this->addCreateButton(route('member.create'), 'member.create');
    }

    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('member.deletes'), 'member.destroy', parent::bulkActions());
    }

    public function getBulkChanges(): array
    {
        return [
            'first_name' => [
                'title' => trans('plugins/member::member.first_name'),
                'type' => 'text',
                'validate' => 'required|max:120',
            ],
            'last_name' => [
                'title' => trans('plugins/member::member.last_name'),
                'type' => 'text',
                'validate' => 'required|max:120',
            ],
            'email' => [
                'title' => trans('core/base::tables.email'),
                'type' => 'text',
                'validate' => 'required|max:120|email',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type' => 'datePicker',
            ],
        ];
    }
}