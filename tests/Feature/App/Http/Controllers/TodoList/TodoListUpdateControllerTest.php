<?php

declare(strict_types=1);

namespace Tests\Feature\App\Http\Controllers\TodoList;

use App\Http\Controllers\TodoList\TodoListUpdateController;
use Database\Factories\TodoListFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class TodoListUpdateControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_todo_list(): void
    {
        $model = TodoListFactory::new()->createOne();
        $newModel = TodoListFactory::new()->makeOne();

        $data = [
            'name' => $newModel->name,
            'description' => $newModel->description,
            'due_date' => $newModel->due_date->format('Y-m-d H:i'),
            'is_done' => true,
        ];

        $response = $this->post(URL::action(TodoListUpdateController::class, $model->getKey()), $data);

        $response->assertFound();

        $this->assertDatabaseHas('todo_lists', [
            'id' => $model->getKey(),
            'name' => $newModel->name,
            'description' => $newModel->description,
            'due_date' => $newModel->due_date->format('Y-m-d H:i').':00',
            'is_done' => 1,
        ]);
    }
}
