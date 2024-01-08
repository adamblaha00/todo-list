<?php

declare(strict_types=1);

namespace App\Http\Controllers\TodoList;

use App\Http\Controllers\TransactionController;
use App\Models\TodoList;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class TodoListStoreController extends TransactionController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(FormRequest $request): SymfonyResponse
    {
        $validator = $this->validateRequest($request);

        if ($validator->fails()) {
            session()->flash('message', 'Formulář obsahuje chyby.');
            session()->flash('alertType', 'error');

            return redirect()->back()->withErrors($validator);
        }

        $validated = $validator->validated();

        $model = new TodoList(
            array_merge(
                Arr::only($validated, ['name', 'description', 'due_date']),
                ['is_done' => false]
            )
        );

        $model->save();

        session()->flash('message', 'Úkol byl úspěšně vytvořen.');
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
            'description' => ['required', 'string'],
            'due_date' => ['required', 'string'],
        ]);
    }
}
