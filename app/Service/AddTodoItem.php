<?php
declare(strict_types=1);

namespace N1215\LaraTodo\Service;

use N1215\LaraTodo\Common\TodoItemInterface;
use N1215\LaraTodo\Common\TodoItemRepositoryInterface;
use N1215\LaraTodo\Exceptions\PersistenceException;

/**
 * Todo項目を新規に追加する
 * @package N1215\LaraTodo\Service
 */
class AddTodoItem
{
    /**
     * @var TodoItemRepositoryInterface
     */
    private $repository;

    /**
     * コンストラクタ
     * @param TodoItemRepositoryInterface $repository
     */
    public function __construct(TodoItemRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param $record
     * @return TodoItemInterface
     * @throws PersistenceException
     */
    public function __invoke(array $record): TodoItemInterface
    {
        $todoItem = $this->repository->new($record);
        return $this->repository->persist($todoItem);
    }
}
