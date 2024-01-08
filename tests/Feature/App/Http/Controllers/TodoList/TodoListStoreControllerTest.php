<?php

declare(strict_types=1);

namespace Tests\Feature\App\Http\Controllers\TodoList;

use App\Http\Controllers\TodoList\TodoListStoreController;
use Database\Factories\TodoListFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class TodoListStoreControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_todo_list(): void
    {
        $model = TodoListFactory::new()
            ->makeOne();

        $data = [
            'name' => $model->name,
            'description' => $model->description,
            'due_date' => $model->due_date->format('Y-m-d H:i'),
        ];

        $response = $this->post(URL::action(TodoListStoreController::class), $data);

        $response->assertFound();

        $this->assertDatabaseHas('todo_lists', [
            'name' => $model->name,
            'description' => $model->description,
            'due_date' => $model->due_date->format('Y-m-d H:i').':00',
        ]);
    }
}
