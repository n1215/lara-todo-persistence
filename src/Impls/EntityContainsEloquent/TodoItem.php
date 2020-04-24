<?php

declare(strict_types=1);

namespace N1215\LaraTodo\Impls\EntityContainsEloquent;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use N1215\LaraTodo\Common\CompletedAt;
use N1215\LaraTodo\Common\Title;
use N1215\LaraTodo\Common\TodoItemId;
use N1215\LaraTodo\Common\TodoItemInterface;

/**
 * Eloquent Modelを中に持つTodoItemエンティティ実装
 * @package N1215\LaraTodo\Impls\EntityContainsEloquent
 */
class TodoItem implements TodoItemInterface
{
    /**
     * @var TodoItemRecord
     */
    private $record;

    /**
     * コンストラクタ
     * @param TodoItemRecord $record
     */
    public function __construct(TodoItemRecord $record)
    {
        $this->record = $record;
    }

    /**
     * @inheritdoc
     */
    public function getId(): TodoItemId
    {
        return TodoItemId::of($this->record->id);
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): Title
    {
        return Title::of($this->record->title);
    }

    /**
     * @inheritdoc
     */
    public function isCompleted(?CarbonInterface $datetime = null): bool
    {
        if ($datetime === null) {
            $datetime = Carbon::now();
        }
        return CompletedAt::of($this->record->completed_at)->isPast($datetime);
    }

    /**
     * @inheritdoc
     */
    public function markAsCompleted(?CarbonInterface $datetime = null): void
    {
        if ($datetime === null) {
            $datetime = Carbon::now();
        }
        $this->record->completed_at = $datetime;
    }

    /**
     * @return TodoItemRecord
     */
    public function getRecord(): TodoItemRecord
    {
        return $this->record;
    }
}
