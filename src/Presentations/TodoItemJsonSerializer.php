<?php

declare(strict_types=1);

namespace N1215\LaraTodo\Presentations;

use Illuminate\Support\Collection;
use N1215\LaraTodo\Common\TodoItemInterface;

/**
 * JSONのためにTodo項目をシリアライズする
 * @package N1215\LaraTodo\Presentations
 */
class TodoItemJsonSerializer
{
    /**
     * @param TodoItemInterface $todoItem
     * @return array
     */
    public function serialize(TodoItemInterface $todoItem): array
    {
        return [
            'id' => $todoItem->getId()->getValue(),
            'title' => $todoItem->getTitle()->getValue(),
            'is_completed' => $todoItem->isCompleted(),
        ];
    }

    /**
     * @param Collection|TodoItemInterface[] $todoItems
     * @return array
     */
    public function serializeCollection(Collection $todoItems): array
    {
        return array_map(
            function (TodoItemInterface $todoItem) {
                return $this->serialize($todoItem);
            },
            $todoItems->all()
        );
    }
}
