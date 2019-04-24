<?php
declare(strict_types=1);

namespace N1215\LaraTodo\Impls\EntityContainsEloquent;

use Illuminate\Support\Collection;
use N1215\LaraTodo\Common\TodoItemId;
use N1215\LaraTodo\Common\TodoItemInterface;
use N1215\LaraTodo\Common\TodoItemRepositoryInterface;
use N1215\LaraTodo\Exceptions\PersistenceException;

class TodoItemRepository implements TodoItemRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function find(TodoItemId $id): ?TodoItemInterface
    {
        /** @var TodoItemRecord $record */
        $record = TodoItemRecord::query()->find($id->getValue());

        if ($record === null) {
            return null;
        }

        return new TodoItem($record);
    }

    /**
     * @inheritDoc
     */
    public function list(): Collection
    {
        return collect(TodoItemRecord::all()->all())
            ->map(function (TodoItemRecord $record) {
                return new TodoItem($record);
            });
    }

    /**
     * @inheritDoc
     */
    public function persist(TodoItemInterface $todoItem): TodoItemInterface
    {
        if (!$todoItem instanceof TodoItem) {
            throw new \InvalidArgumentException('このリポジトリで永続化できるエンティティは' . TodoItem::class. 'のみです');
        }

        $record = $todoItem->getRecord();
        if (!$record->save()) {
            throw new PersistenceException('Todo項目の永続化に失敗しました。title=' . $todoItem->getTitle()->getValue());
        }

        return $todoItem;
    }

    /**
     * @inheritDoc
     */
    public function new(array $record): TodoItemInterface
    {
        return new TodoItem((new TodoItemRecord())->forceFill($record));
    }
}
