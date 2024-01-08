<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\TodoList;
use Database\Factories\TodoListFactory;
use Illuminate\Database\Seeder;

class TodoListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (TodoList::query()->getQuery()->exists()) {
            return;
        }

        TodoListFactory::new()
            ->count(10)
            ->create();
    }
}
