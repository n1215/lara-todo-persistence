<?php

declare(strict_types=1);

namespace N1215\LaraTodo\Impls\EloquentAsEntity;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use N1215\LaraTodo\Common\CompletedAt;
use N1215\LaraTodo\Common\Title;
use N1215\LaraTodo\Common\TodoItemId;
use N1215\LaraTodo\Common\TodoItemInterface;

/**
 * Eloquent ModelによるTodoItemエンティティ実装
 * @package N1215\LaraTodo\Impls\EloquentAsEntity
 *
 * @property int|null $id Todo項目のID
 * @property string $title Todo項目のタイトル
 * @property Carbon|null $completed_at 完了日時
 * @property Carbon $created_at 作成日時
 * @property Carbon $updated_at 更新日時
 */
class TodoItem extends Model implements TodoItemInterface
{
    protected $dates = ['completed_at'];

    /**
     * @inheritdoc
     */
    public function getId(): TodoItemId
    {
        return TodoItemId::of($this->id);
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): Title
    {
        return Title::of($this->title);
    }

    /**
     * @inheritdoc
     */
    public function isCompleted(?CarbonInterface $datetime = null): bool
    {
        if ($datetime === null) {
            $datetime = Carbon::now();
        }
        return CompletedAt::of($this->completed_at)->isPast($datetime);
    }

    /**
     * @inheritdoc
     */
    public function markAsCompleted(?CarbonInterface $datetime = null): void
    {
        if ($datetime === null) {
            $datetime = Carbon::now();
        }
        $this->completed_at = $datetime;
    }
}
