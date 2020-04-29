<?php

declare(strict_types=1);

namespace N1215\LaraTodo\Impls\EloquentAsEntity;

use Illuminate\Support\Collection;
use InvalidArgumentException;
use N1215\LaraTodo\Common\TodoItemId;
use N1215\LaraTodo\Common\TodoItemInterface;
use N1215\LaraTodo\Common\TodoItemRepositoryInterface;
use N1215\LaraTodo\Exceptions\PersistenceException;

/**
 * Eloquent ModelによるTodoItemのリポジトリ実装
 * @package N1215\LaraTodo\Impls\EloquentAsEntity
 */
class TodoItemRepository implements TodoItemRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function find(TodoItemId $id): ?TodoItemInterface
    {
        /** @var TodoItem|null $todoItem */
        $todoItem = TodoItem::query()->find($id->getValue());
        return $todoItem;
    }

    /**
     * @inheritDoc
     */
    public function list(): Collection
    {
        return collect(TodoItem::all()->all());
    }

    /**
     * @inheritDoc
     */
    public function persist(TodoItemInterface $todoItem): TodoItemInterface
    {
        if (!$todoItem instanceof TodoItem) {
            throw new InvalidArgumentException('このリポジトリで永続化できるエンティティは' . TodoItem::class . 'のみです');
        }

        if (!$todoItem->save()) {
            throw new PersistenceException('Todo項目の永続化に失敗しました。title=' . $todoItem->getTitle()->getValue());
        }

        return $todoItem;
    }

    /**
     * @inheritDoc
     */
    public function new(array $record): TodoItemInterface
    {
        return (new TodoItem())->forceFill($record);
    }
}
