<?php

declare(strict_types=1);

namespace N1215\LaraTodo\Service;

use Illuminate\Support\Collection;
use N1215\LaraTodo\Common\TodoItemInterface;
use N1215\LaraTodo\Common\TodoItemRepositoryInterface;

/**
 * Todo項目の一覧を返す
 * @package N1215\LaraTodo\Service
 */
class ListTodoItems
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
     * @return Collection|TodoItemInterface[]
     */
    public function __invoke(): Collection
    {
        return $this->repository->list();
    }
}
