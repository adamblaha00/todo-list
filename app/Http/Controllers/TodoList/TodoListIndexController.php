<?php

declare(strict_types=1);

namespace App\Http\Controllers\TodoList;

use App\Http\Controllers\Controller;
use App\Models\TodoList;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\View\View;

class TodoListIndexController extends Controller
{
    /**
     * Take.
     */
    public const TAKE = \PHP_INT_MAX;

    /**
     * Sort.
     */
    public const SORT = ['id', '-id', 'created_at', '-created_at', 'updated_at', '-updated_at', 'name', '-name'];

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View|RedirectResponse
    {
        $validator = $this->validateRequest($request);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $validated = $validator->validated();

        $builder = TodoList::query();

        $this->select($builder);

        $this->filterSearch($builder, $validated);
        $this->filterId($builder, $validated);
        $this->filterStatus($builder, $validated);

        $this->sort($builder, $validated);

        $data = $builder->paginate($validated['take'] ?? self::TAKE);

        return view('todo_lists.index', [
            'todoLists' => $data,
        ]);
    }

    /**
     * Modify select query.
     */
    protected function select(Builder $builder): void
    {
        $builder->getQuery()->select($builder->qualifyColumn('*'));
    }

    /**
     * Filter by search.
     */
    protected function filterSearch(Builder $builder, array $validated): void
    {
        if (!Arr::has($validated, 'filter.search')) {
            return;
        }

        TodoList::scopeSearch($builder, Arr::get($validated, 'filter.search'));
    }

    /**
     * Filter by id.
     */
    protected function filterId(Builder $builder, array $validated): void
    {
        if (!Arr::has($validated, 'filter.id')) {
            return;
        }

        TodoList::scopeKey($builder, Arr::get($validated, 'filter.id'));
    }

    /**
     * Filter by status.
     */
    protected function filterStatus(Builder $builder, array $validated): void
    {
        if (!Arr::has($validated, 'filter.status')) {
            return;
        }

        if ((int) Arr::get($validated, 'filter.status') === 1) {
            TodoList::scopePast($builder);
        }

        if ((int) Arr::get($validated, 'filter.status') === 2) {
            TodoList::scopeUpcoming($builder);
        }
    }

    /**
     * Sort query.
     */
    protected function sort(Builder $builder, array $validated): void
    {
        $sorts = $validated['sort'] ?? [];

        if (! \in_array('id', $sorts, true) && ! \in_array('-id', $sorts, true)) {
            $sorts[] = '-id';
        }

        foreach ($sorts as $sort) {
            if (\str_starts_with($sort, '-')) {
                $builder->getQuery()->orderByDesc($builder->qualifyColumn(\mb_substr($sort, 1)));

                continue;
            }

            $builder->getQuery()->orderBy($builder->qualifyColumn($sort));
        }
    }

    /**
     * Validate the incoming request.
     */
    protected function validateRequest(Request $request): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($request->all(), [
            'filter.search' => ['nullable', 'string'],
            'filter.id' => ['nullable', 'array'],
            'filter.id.*' => ['nullable', 'string'],
            'filter.status' => ['nullable', 'integer', 'in:1,2'],
            'take' => ['nullable', 'integer', 'max:' . self::TAKE],
            'sort' => ['nullable', 'array'],
            'sort.*' => ['nullable', 'string', 'in:' . implode(',', self::SORT)],
        ]);
    }
}
