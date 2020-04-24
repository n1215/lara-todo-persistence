<?php

declare(strict_types=1);

namespace N1215\LaraTodo\Impls\POPOAndEloquent;

use Illuminate\Support\Collection;
use InvalidArgumentException;
use N1215\LaraTodo\Common\TodoItemId;
use N1215\LaraTodo\Common\TodoItemInterface;
use N1215\LaraTodo\Common\TodoItemRepositoryInterface;
use N1215\LaraTodo\Exceptions\PersistenceException;

/**
 * POPOのエンティティのためのEloquentによるリポジトリ
 * Class TodoItemRepository
 * @package N1215\LaraTodo\Impls\POPOAndEloquent
 */
class TodoItemRepository implements TodoItemRepositoryInterface
{
    /**
     * @var TodoItemFactory
     */
    private $factory;

    /**
     * コンストラクタ
     * @param TodoItemFactory $factory
     */
    public function __construct(TodoItemFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @inheritDoc
     */
    public function find(TodoItemId $id): ?TodoItemInterface
    {
        /** @var TodoItemRecord|null $record */
        $record = TodoItemRecord::query()->find($id->getValue());

        if ($record === null) {
            return null;
        }

        return $this->factory->fromRecord($record);
    }

    /**
     * @inheritDoc
     */
    public function list(): Collection
    {
        /** @var TodoItemRecord[] $records */
        $records = TodoItemRecord::all()->all();

        return collect($records)
            ->map(function (TodoItemRecord $record) {
                return $this->factory->fromRecord($record);
            });
    }

    /**
     * @inheritDoc
     */
    public function persist(TodoItemInterface $todoItem): TodoItemInterface
    {
        if (!$todoItem instanceof TodoItem) {
            throw new InvalidArgumentException('このリポジトリで永続化できるエンティティは' . TodoItem::class. 'のみです');
        }

        /** @var TodoItemRecord|null $record */
        $record = TodoItemRecord::query()->find($todoItem->getId()->getValue());

        if ($record === null) {
            $record = new TodoItemRecord();
        }

        $record->title = $todoItem->getTitle()->getValue();
        $record->completed_at = $todoItem->getCompletedAt()->getValue();

        if (!$record->save()) {
            throw new PersistenceException('Todo項目の永続化に失敗しました。title=' . $todoItem->getTitle()->getValue());
        }

        return $this->factory->fromRecord($record);
    }

    /**
     * @inheritDoc
     */
    public function new(array $record): TodoItemInterface
    {
        return $this->factory->fromArray($record);
    }
}
