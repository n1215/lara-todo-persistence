<?php
declare(strict_types=1);

namespace N1215\LaraTodo\Impls\POPOAndAtlas;

use Atlas\Orm\Atlas;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use N1215\LaraTodo\Common\TodoItemId;
use N1215\LaraTodo\Common\TodoItemInterface;
use N1215\LaraTodo\Common\TodoItemRepositoryInterface;
use N1215\LaraTodo\Exceptions\PersistenceException;

/**
 * POPOのエンティティのためのAtlas.Ormによるレポジトリ
 * Class TodoItemRepository
 * @package N1215\LaraTodo\Impls\POPOAndAtlas
 */
class TodoItemRepository implements TodoItemRepositoryInterface
{
    /**
     * @var TodoItemFactory
     */
    private $factory;

    /**
     * @var Atlas
     */
    private $atlas;

    /**
     * @inheritDoc
     */
    public function __construct(TodoItemFactory $factory, Atlas $atlas)
    {
        $this->factory = $factory;
        $this->atlas = $atlas;
    }

    /**
     * @inheritDoc
     */
    public function find(TodoItemId $id): ?TodoItemInterface
    {
        /** @var TodoItemRecord $record */
        $record = $this->atlas->fetchRecord(TodoItemMapper::class, $id->getValue());

        if (is_null($record)) {
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
        $records = $this->atlas
            ->select(TodoItemMapper::class)
            ->fetchRecords();

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
            throw new \InvalidArgumentException('このリポジトリで永続化できるエンティティは' . TodoItem::class. 'のみです');
        }

        $now = Carbon::now()->format('Y-m-d H:i:s');

        /** @var TodoItemRecord $record */
        if (is_null($todoItem->getId()->getValue())) {
            $record = $this->atlas->newRecord(TodoItemMapper::class);
            $record->created_at = $now;
        } else {
            $record = $this->atlas->fetchRecord(TodoItemMapper::class, $todoItem->getId()->getValue());
        }

        $record->title = $todoItem->getTitle()->getValue();
        $record->completed_at = $todoItem->isCompleted()
            ? $todoItem->getCompletedAt()->getValue()->format('Y-m-d H:i:s')
            : null;
        $record->updated_at = $now;

        if (!$this->atlas->persist($record)) {
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
