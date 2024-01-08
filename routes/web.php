<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::prefix('v1/todo_lists')
    ->group(static function (): void {
       Route::get('/', \App\Http\Controllers\TodoList\TodoListIndexController::class);
       Route::post('store', \App\Http\Controllers\TodoList\TodoListStoreController::class)->name('todo_lists.store');
       Route::post('update/{model}', \App\Http\Controllers\TodoList\TodoListUpdateController::class)->name('todo_lists.update');
       Route::post('destroy/{model}', \App\Http\Controllers\TodoList\TodoListDestroyController::class)->name('todo_lists.destroy');
    });

Route::get('/', function () {
    return view('welcome');
});
