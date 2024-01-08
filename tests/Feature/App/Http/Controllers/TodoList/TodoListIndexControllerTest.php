<?php

declare(strict_types=1);

namespace Tests\Feature\App\Http\Controllers\TodoList;

use App\Http\Controllers\TodoList\TodoListIndexController;
use Database\Factories\TodoListFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class TodoListIndexControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_todo_list(): void
    {
        $model = TodoListFactory::new()->createOne();

        $query = [
            'filter' => [
                'id' => [$model->getKey()],
                'search' => $model->name,
                'status' => 2,
            ],
            'take' => TodoListIndexController::TAKE,
            'sort' => TodoListIndexController::SORT,
            'page' => 1,
        ];

        $response = $this->get(URL::action(TodoListIndexController::class, $query));

        $response->assertOk();
    }
}
