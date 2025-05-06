<?php

namespace SheavesCapital\FilamentKanban;

use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use SheavesCapital\FilamentKanban\Commands\MakeKanbanBoardCommand;
use SheavesCapital\FilamentKanban\Testing\TestsFilamentKanban;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentKanbanServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-kanban';

    public static string $viewNamespace = 'filament-kanban';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasAssets()
            ->hasCommands($this->getCommands());

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageBooted(): void
    {

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/filament-kanban/{$file->getFilename()}"),
                ], 'filament-kanban-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsFilamentKanban);
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            MakeKanbanBoardCommand::class,
        ];
    }
}
