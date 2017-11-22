<?php
declare(strict_types=1);

namespace N1215\LaraTodo\Impls\POPOAndPDO;

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
 * @package N1215\LaraTodo\Impls\POPOAndPDO
 */
class TodoItemRepository implements TodoItemRepositoryInterface
{
    const SQL_SELECT = "select * from todo_items where id = :id limit 1";

    const SQL_SELECT_ALL = "select * from todo_items";

    const SQL_INSERT = "insert into todo_items (title, completed_at, created_at, updated_at) VALUES (:title, :completed_at, :created_at, :updated_at)";

    const SQL_UPDATE = "update todo_items set title = :title, completed_at = :completed_at, updated_at = :updated_at where id = :id";

    /**
     * @var TodoItemFactory
     */
    private $factory;

    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @inheritDoc
     */
    public function __construct(TodoItemFactory $factory, \PDO $pdo)
    {
        $this->factory = $factory;
        $this->pdo = $pdo;
    }

    /**
     * @inheritDoc
     */
    public function find(TodoItemId $id): ?TodoItemInterface
    {
        $rawId = $id->getValue();
        $statement = $this->pdo->prepare(self::SQL_SELECT);
        $statement->bindParam(':id', $rawId);
        $statement->execute();
        $record = $statement->fetch();
        $statement->closeCursor();

        if (!$record) {
            return null;
        }

        return $this->factory->fromArray($record);
    }

    /**
     * @inheritDoc
     */
    public function list(): Collection
    {
        $statement = $this->pdo->prepare(self::SQL_SELECT_ALL);
        $statement->execute();
        $records = $statement->fetchAll();

        return collect($records)
            ->map(function (array $record) {
                return $this->factory->fromArray($record);
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
        ];

        // 更新
        if (!is_null($rawId)) {
            try {
                $statement = $this->pdo->prepare(self::SQL_UPDATE);
                $statement->bindParam(':title', $values['title']);
                $statement->bindParam(':completed_at', $values['completed_at']);
                $statement->bindParam(':updated_at', $now);
                $statement->bindParam(':id', $rawId);
                $statement->execute();
            } catch(\Exception $e) {
                logger()->error($e);
                throw new PersistenceException('Todo項目の永続化に失敗しました。title=' . $todoItem->getTitle()->getValue(), 0, $e);
            }

            return $todoItem;
        }

        // 新規追加
        try {
            $statement = $this->pdo->prepare(self::SQL_INSERT);
            $statement->bindParam(':title', $values['title']);
            $statement->bindParam(':completed_at', $values['completed_at']);
            $statement->bindParam(':created_at', $now);
            $statement->bindParam(':updated_at', $now);
            $statement->execute();
            $rawId = $this->pdo->lastInsertId();
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
