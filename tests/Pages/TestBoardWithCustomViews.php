<?php

namespace SheavesCapital\FilamentKanban\Tests\Pages;

use Filament\Forms\Components\TextInput;
use SheavesCapital\FilamentKanban\Pages\KanbanBoard;
use SheavesCapital\FilamentKanban\Tests\Enums\TaskStatus;
use SheavesCapital\FilamentKanban\Tests\Models\Task;

class TestBoardWithCustomViews extends KanbanBoard
{
    protected static string $model = Task::class;

    protected static string $statusEnum = TaskStatus::class;

    protected static string $recordView = 'kanban-record';

    protected function getEditModalFormSchema(null | int | string $recordId): array
    {
        return [
            TextInput::make('title'),
        ];
    }
}
