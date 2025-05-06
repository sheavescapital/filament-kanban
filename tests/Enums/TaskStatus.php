<?php

namespace SheavesCapital\FilamentKanban\Tests\Enums;

use SheavesCapital\FilamentKanban\Concerns\IsKanbanStatus;

enum TaskStatus: string
{
    use IsKanbanStatus;

    case Todo = 'Todo';
    case Doing = 'Doing';
    case Done = 'Done';
}
