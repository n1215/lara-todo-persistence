<?php

declare(strict_types=1);

namespace N1215\LaraTodo\Service;

use N1215\LaraTodo\Common\TodoItemId;
use N1215\LaraTodo\Common\TodoItemInterface;
use N1215\LaraTodo\Common\TodoItemRepositoryInterface;
use N1215\LaraTodo\Exceptions\EntityNotFoundException;

/**
 * 指定したTodo項目を返す
 * @package N1215\LaraTodo\Service
 */
class ShowTodoItem
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
     */
    public function __invoke(TodoItemId $id)
    {
        $todoItem = $this->repository->find($id);

        if ($todoItem === null) {
            throw new EntityNotFoundException('entity not found. id=' . $id->getValue());
        }

        return $todoItem;
    }
}
