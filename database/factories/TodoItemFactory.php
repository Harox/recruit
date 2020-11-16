<?php

use App\TodoItem;
use App\User;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

function getUserIdArray()
{
    $userIds = [];

    $users = User::select('id')->get();

    foreach ($users as $user) {
        $userIds[] = $user->id;
    }

    return $userIds;
}

$factory->define(App\TodoItem::class, function (Faker $faker) {
    $randomStatus = $faker->randomElement(['pending', 'completed']);
    $randomUserId = $faker->randomElement(getUserIdArray());

    return [
        'title' => $faker->sentence,
        'status' => $randomStatus,
        'user_id' => $randomUserId
    ];
});

$factory->afterCreating(App\TodoItem::class, function ($todoItem, $faker) {
    if ($todoItem->position == 0) {
        $pendingPosition = $completedPosition = 0;
        $pendingTodos = TodoItem::where(['user_id' => $todoItem->user_id, 'status' => 'pending'])->get();
        $completedTodos = TodoItem::where(['user_id' => $todoItem->user_id, 'status' => 'completed'])->get();

        foreach ($pendingTodos as $pendingTodo) {
            $pendingTodo->position = $pendingPosition + 1;
            $pendingTodo->save();
            $pendingPosition += 1;
        }

        foreach ($completedTodos as $completedTodo) {
            $completedTodo->position = $completedPosition + 1;
            $completedTodo->save();
            $completedPosition += 1;
        }
    }
});
