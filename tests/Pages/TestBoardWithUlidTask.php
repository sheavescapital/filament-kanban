<?php

namespace SheavesCapital\FilamentKanban\Tests\Pages;

use SheavesCapital\FilamentKanban\Pages\KanbanBoard;
use SheavesCapital\FilamentKanban\Tests\Enums\TaskStatus;
use SheavesCapital\FilamentKanban\Tests\Models\UlidTask;

class TestBoardWithUlidTask extends KanbanBoard
{
    protected static string $model = UlidTask::class;

    protected static string $statusEnum = TaskStatus::class;
}
