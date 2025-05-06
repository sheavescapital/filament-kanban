# Add kanban boards to your Filament pages

A fork of [(https://github.com/mokhosh/filament-kanban)](https://github.com/mokhosh/filament-kanban)

## Installation

You can install the package via composer:

```bash
composer require sheavescapital/filament-kanban
```

Publish the assets so the styles are correct:

```bash
php artisan filament:assets
```

## Before You Start

> [!IMPORTANT]  
> You should have some `Model` with a `status` column. This column can be called `status` in the database or anything else.

I'm also assuming there's a `title` column on your model, but you can have `name` or any other column to represent a title.

I recommend you create a string backed `Enum` to define your statuses.

You can use our `IsKanbanStatus` trait, so you can easily transform your enum cases for the Kanban board using the `statuses` method on your enum.

```php
use SheavesCapital\FilamentKanban\Concerns\IsKanbanStatus;

enum UserStatus: string
{
    use IsKanbanStatus;

    case User = 'User';
    case Admin = 'Admin';
}
```

I recommend you cast the `status` attribute on your `Model` to the enum that you have created.

> [!TIP]
> I also recommend you use the [Spatie Eloquent Sortable](https://github.com/spatie/eloquent-sortable) package on your `Model`, and we will magically add sorting abilities to your Kanban boards.

## Usage

You can create a new Kanban board called `UsersKanbanBoard` using this artisan command:

```php
php artisan make:kanban UsersKanbanBoard
```

This creates a good starting point for your Kanban board. You can customize the Kanban board to your liking.

You should override the `model` property, so we can load your records.

```php
protected static string $model = User::class;
```

You should also override the `statusEnum` property, which defines your statuses.

```php
protected static string $statusEnum = UserStatus::class;
```

## Upgrade Guide

If you have version 1.x on your application, and you want to upgrade to version 2.x, here is your checklist:

- [ ] You need to override `$model` and `$statusEnum` as mentioned in [the last part](#usage)
- [ ] If you have published `kanban-record.blade.php` view, you can use `$record` as a `Model` instance instead of an `array`.
- [ ] If you're overriding `KanbanBoard` methods just to do the default behaviour, you can safely remove them now. You should be able to get away with overriding 0 methods, if you don't have special requirements 🥳

## Advanced Usage

You can override the `records` method, to customize how the records or items that you want to see on your board are retrieved.

```php
protected function records(): Collection
{
    return User::where('role', 'admin')->get();
}
```

If you don't want to define an `Enum` for your statuses, or you have a special logic for retrieving your statuses, you can override the `statuses` method:

```php
protected function statuses(): Collection
{
     return collect([
         ['id' => 'user', 'title' => 'User'],
         ['id' => 'admin', 'title' => 'Admin'],
     ]);
}
```

You can also override these methods to change your board's behavior when records are dragged and dropped:
- `onStatusChanged` which defines what happens when a record is moved between statuses.
- `onSortChanged` which defines what happens when a record is moved inside the same status.

```php
public function onStatusChanged(int $recordId, string $status, array $fromOrderedIds, array $toOrderedIds): void
{
    User::find($recordId)->update(['status' => $status]);
    User::setNewOrder($toOrderedIds);
}

public function onSortChanged(int $recordId, string $status, array $orderedIds): void
{
    User::setNewOrder($orderedIds);
}
```

### Customizing the Status Enum

If you add `IsKanbanStatus` to your status `Enum`, this trait adds a static `statuses()` method to your enum that will return the statuses defined in your enum in the appropriate format.

If you don't want all cases of your enum to be present on the board, you can override this method and return a subset of cases:

```php
public static function kanbanCases(): array
{
    return [
        static::CaseOne,
        static::CaseThree,
    ];
}
```

`IsKanbanStatus` uses the `value` of your cases for the `title` of your statuses. You can customize how the title is retrieved as well:

```php
public function getTitle(): string
{
    return __($this->label());
}
```

## Edit modal

### Disabling the modal

Edit modal is enabled by default, and you can show it by clicking on records.

If you need to disable the edit modal override this property:

```php
public bool $disableEditModal = false;
```

### Edit modal form schema

You can define the edit modal form schema by overriding this method:

```php
protected function getEditModalFormSchema(null|int $recordId): array
{
    return [
        TextInput::make('title'),
    ];
}
```

As you can see you have access to the `id` of the record being edited, if that's helpful in building your schema.

### Customizing edit form submit action

You can define what happens when the edit form is submitted by overriding this method:

```php
protected function editRecord($recordId, array $data, array $state): void
{
    Model::find($recordId)->update([
        'phone' => $data['phone']
    ]);
}
```

The `data` array contains the form data, and the `state` array contains the full record data.

### Customizing modal's appearance

You can customize modal's title, size and the labels for save and cancel buttons, or use Filament's slide-over instead of a modal:

```php
protected string $editModalTitle = 'Edit Record';

protected string $editModalWidth = '2xl';

protected string $editModalSaveButtonLabel = 'Save';

protected string $editModalCancelButtonLabel = 'Cancel';

protected bool $editModalSlideOver = true;
```

## Customization

### Changing the navigation icon

```php
protected static ?string $navigationIcon = 'heroicon-o-document-text';
```

### Changing the model property that's used as the title

```php
protected static string $recordTitleAttribute = 'title';
```

### Changing the model property that's used as the status

```php
protected static string $recordStatusAttribute = 'status';
```

### Customizing views

You can publish the views using this artisan command:

```bash
php artisan vendor:publish --tag="filament-kanban-views"
```

I recommend you delete the files that you don't intend to customize and keep the ones you want to change.
This way you will get any possible future updates for the original views.

The above method will replace the views for all Kanban boards in your applications.

Alternatively, you might want to change views for one of your boards. You can override each view by overriding these properties:

```php
protected static string $view = 'filament-kanban::kanban-board';

protected static string $headerView = 'filament-kanban::kanban-header';

protected static string $recordView = 'filament-kanban::kanban-record';

protected static string $statusView = 'filament-kanban::kanban-status';

protected static string $scriptsView = 'filament-kanban::kanban-scripts';
```


## Testing

```bash
composer test
```

## Credits

- [Mo Khosh](https://github.com/mokhosh)
- [All Contributors](../../contributors)
- This original idea and structure of this package borrows heavily from [David Vincent](https://github.com/invaders-xx)'s [filament-kanban-board](https://github.com/invaders-xx/filament-kanban-board/)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
