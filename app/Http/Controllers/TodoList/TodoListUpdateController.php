<?php

declare(strict_types=1);

namespace App\Http\Controllers\TodoList;

use App\Http\Controllers\TransactionController;
use App\Models\TodoList;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class TodoListUpdateController extends TransactionController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, TodoList $model): SymfonyResponse
    {
        $validator = $this->validateRequest($request);

        if ($validator->fails()) {
            session()->flash('message', 'Formulář obsahuje chyby.');
            session()->flash('alertType', 'error');

            return redirect()->back()->withErrors($validator);
        }

        $validated = $validator->validated();

        $model->update(Arr::only($validated, ['name', 'description', 'due_date', 'is_done']));

        session()->flash('message', 'Úkol byl úspěšně aktualizován.');
        session()->flash('alertType', 'success');

        return redirect()->back();
    }

    /**
     * Validate the incoming request.
     */
    protected function validateRequest(Request $request): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'due_date' => ['required', 'string'],
            'is_done' => ['required', 'boolean'],
        ]);
    }
}
