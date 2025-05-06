<?php

use Illuminate\Database\Eloquent\Model;
use SheavesCapital\FilamentKanban\Tests\Models\Task;
use SheavesCapital\FilamentKanban\Tests\Pages\TestBoard;

use function Pest\Laravel\actingAs;

it('doesnt break if model should be strict', function () {
    Model::shouldBeStrict();

    $task = Task::factory()->create();

    actingAs($this->admin)
        ->get(TestBoard::getUrl())
        ->assertSee($task->title);

    Model::shouldBeStrict(false);
});
