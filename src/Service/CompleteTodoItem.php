<?php

declare(strict_types=1);

namespace N1215\LaraTodo\Service;

use N1215\LaraTodo\Common\TodoItemId;
use N1215\LaraTodo\Common\TodoItemInterface;
use N1215\LaraTodo\Common\TodoItemRepositoryInterface;
use N1215\LaraTodo\Exceptions\EntityNotFoundException;
use N1215\LaraTodo\Exceptions\PersistenceException;

/**
 * Todo項目を完了済みにする
 * @package N1215\LaraTodo\Service
 */
class CompleteTodoItem
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
     * @param TodoItemId $id
     * @return TodoItemInterface
     * @throws EntityNotFoundException
     * @throws PersistenceException
     */
    public function __invoke(TodoItemId $id): TodoItemInterface
    {
        $todoItem = $this->repository->find($id);

        if ($todoItem === null) {
            throw new EntityNotFoundException('entity not found. id=' . $id->getValue());
        }

        $todoItem->markAsCompleted();
        return $this->repository->persist($todoItem);
    }
}
