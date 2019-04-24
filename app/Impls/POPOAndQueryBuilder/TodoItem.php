<?php
declare(strict_types=1);

namespace N1215\LaraTodo\Impls\POPOAndQueryBuilder;

use Carbon\Carbon;
use N1215\LaraTodo\Common\CompletedAt;
use N1215\LaraTodo\Common\Title;
use N1215\LaraTodo\Common\TodoItemId;
use N1215\LaraTodo\Common\TodoItemInterface;

/**
 * POPOのTodoItemエンティティ実装
 * Class TodoItem
 * @package N1215\LaraTodo\Impls\POPOAndQueryBuilder
 */
class TodoItem implements TodoItemInterface
{
    /**
     * @var TodoItemId
     */
    private $id;

    /**
     * @var Title
     */
    private $title;

    /**
     * @var CompletedAt
     */
    private $completedAt;

    /**
     * コンストラクタ
     * @param TodoItemId $id
     * @param Title $title
     * @param CompletedAt $completedAt
     */
    public function __construct(TodoItemId $id, Title $title, CompletedAt $completedAt)
    {
        $this->id = $id;
        $this->title = $title;
        $this->completedAt = $completedAt;
    }

    /**
     * @inheritDoc
     */
    public function getId(): TodoItemId
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): Title
    {
        return $this->title;
    }

    /**
     * @inheritDoc
     */
    public function isCompleted(?Carbon $datetime = null): bool
    {
        if ($datetime === null) {
            $datetime = Carbon::now();
        }
        return $this->completedAt->isPast($datetime);
    }

    /**
     * @inheritDoc
     */
    public function markAsCompleted(?Carbon $datetime = null): void
    {
        if ($datetime === null) {
            $datetime = Carbon::now();
        }
        $this->completedAt = CompletedAt::of($datetime);
    }

    /**
     * 完了日時を取得
     * @return CompletedAt
     */
    public function getCompletedAt(): CompletedAt
    {
        return $this->completedAt;
    }
}
