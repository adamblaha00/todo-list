<?php

declare(strict_types=1);

namespace Tests\Feature\App\Http\Controllers\TodoList;

use App\Http\Controllers\TodoList\TodoListDestroyController;
use Database\Factories\TodoListFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class TodoListDestroyControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_destroy_todo_list(): void
    {
        $model = TodoListFactory::new()->createOne();

        $response = $this->post(URL::action(TodoListDestroyController::class, $model->getKey()));

        $response->assertFound();
    }
}
