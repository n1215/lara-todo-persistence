<?php
declare(strict_types=1);

namespace N1215\LaraTodo\Impls\POPOAndQueryBuilder;

use Carbon\Carbon;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Collection;
use N1215\LaraTodo\Common\TodoItemId;
use N1215\LaraTodo\Common\TodoItemInterface;
use N1215\LaraTodo\Common\TodoItemRepositoryInterface;
use N1215\LaraTodo\Exceptions\PersistenceException;

/**
 * POPOのエンティティのためのQuery Builderによるレポジトリ
 * Class TodoItemRepository
 * @package N1215\LaraTodo\Impls\POPOAndQueryBuilder
 */
class TodoItemRepository implements TodoItemRepositoryInterface
{
    const TABLE_NAME = 'todo_items';

    /**
     * @var TodoItemFactory
     */
    private $factory;

    /**
     * @var \Illuminate\Database\Connection
     */
    private $conn;

    /**
     * @inheritDoc
     */
    public function __construct(TodoItemFactory $factory, DatabaseManager $dbManager)
    {
        $this->factory = $factory;
        $this->conn = $dbManager->connection('sqlite');
    }

    /**
     * @inheritDoc
     */
    public function find(TodoItemId $id): ?TodoItemInterface
    {
        $record = $this->conn
            ->table(self::TABLE_NAME)
            ->find($id->getValue());

        if (is_null($record)) {
            return null;
        }

        return $this->factory->fromArray((array)$record);
    }

    /**
     * @inheritDoc
     */
    public function list(): Collection
    {
        $records = $this->conn
            ->table(self::TABLE_NAME)
            ->get();

        return collect($records)
            ->map(function ($record) {
                return $this->factory->fromArray((array)$record);
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

        $rawId = $todoItem->getId()->getValue();
        $completedAt = $todoItem->getCompletedAt()->getValue();
        $rawCompletedAt = $completedAt ? $completedAt->format('Y-m-d H:i:s') : null;
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $values = [
            'title' => $todoItem->getTitle()->getValue(),
            'completed_at' => $rawCompletedAt,
            'updated_at' => $now,
        ];

        // 更新
        if (!is_null($rawId)) {
            try {
                $this->conn->table(self::TABLE_NAME)->where('id', $rawId)->update($values);
            } catch(\Exception $e) {
                logger()->error($e);
                throw new PersistenceException('Todo項目の永続化に失敗しました。title=' . $todoItem->getTitle()->getValue(), 0, $e);
            }

            return $todoItem;
        }

        // 新規追加
        try {
            $values['created_at'] = $now;
            $rawId = $this->conn->table(self::TABLE_NAME)->insertGetId($values);
        } catch(\Exception $e) {
            throw new PersistenceException('Todo項目の永続化に失敗しました。title=' . $todoItem->getTitle()->getValue(), 0, $e);
        }

        return $this->factory->fromArray(array_merge($values, ['id' => $rawId]));
    }

    /**
     * @inheritDoc
     */
    public function new(array $record): TodoItemInterface
    {
        return $this->factory->fromArray($record);
    }
}
