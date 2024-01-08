<?php

declare(strict_types=1);

namespace App\Http\Controllers\TodoList;

use App\Http\Controllers\TransactionController;
use App\Models\TodoList;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class TodoListDestroyController extends TransactionController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, TodoList $model): SymfonyResponse
    {
        $model->delete();

        session()->flash('message', 'Úkol byl úspěšně smazán.');
        session()->flash('alertType', 'success');

        return redirect()->back();
    }
}
