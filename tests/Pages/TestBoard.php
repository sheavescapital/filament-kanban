<?php

namespace SheavesCapital\FilamentKanban\Tests\Pages;

use Filament\Forms;
use SheavesCapital\FilamentKanban\Pages\KanbanBoard;
use SheavesCapital\FilamentKanban\Tests\Enums\TaskStatus;
use SheavesCapital\FilamentKanban\Tests\Models\Task;
use SheavesCapital\FilamentKanban\Tests\Models\User;

class TestBoard extends KanbanBoard
{
    protected static string $model = Task::class;

    protected static string $statusEnum = TaskStatus::class;

    protected function getEditModalFormSchema(null | int | string $recordId): array
    {
        return [
            Forms\Components\TextInput::make('title')->required(),
            Forms\Components\Select::make('team')
                ->relationship('team', 'name')
                ->multiple()
                ->options(User::pluck('name', 'id')),
        ];
    }
}
