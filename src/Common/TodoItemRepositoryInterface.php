<?php
declare(strict_types=1);

namespace N1215\LaraTodo\Common;

use Illuminate\Support\Collection;
use N1215\LaraTodo\Exceptions\PersistenceException;

/**
 * Todo項目のリポジトリのインターフェース
 * @package N1215\LaraTodo\Common
 */
interface TodoItemRepositoryInterface
{
    /**
     * IDを指定してエンティティを取得
     * @param TodoItemId $id
     * @return TodoItemInterface|null
     */
    public function find(TodoItemId $id): ?TodoItemInterface;

    /**
     * 全エンティティを取得
     * @return Collection|TodoItemInterface[]
     */
    public function list(): Collection;

    /**
     * エンティティを永続化
     * @param TodoItemInterface $todoItem
     * @return TodoItemInterface
     * @throws PersistenceException
     */
    public function persist(TodoItemInterface $todoItem): TodoItemInterface;

    /**
     * エンティティを初期化
     * @param array $record
     * @return TodoItemInterface
     */
    public function new(array $record): TodoItemInterface;
}
