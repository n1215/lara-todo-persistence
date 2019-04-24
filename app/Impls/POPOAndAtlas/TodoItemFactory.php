<?php
declare(strict_types=1);

namespace N1215\LaraTodo\Impls\POPOAndAtlas;

use Carbon\Carbon;
use N1215\LaraTodo\Common\CompletedAt;
use N1215\LaraTodo\Common\Title;
use N1215\LaraTodo\Common\TodoItemId;
use N1215\LaraTodo\Common\TodoItemInterface;
use N1215\LaraTodo\Impls\POPOAndAtlas\TodoItem\TodoItemRecord;

/**
 * POPOのエンティティ実装のファクトリ
 * Class TodoItemFactory
 * @package N1215\LaraTodo\Impls\POPOAndAtlas
 */
class TodoItemFactory
{
    /**
     * DBレコードから作成
     * @param TodoItemRecord $record
     * @return TodoItemInterface
     */
    public function fromRecord(TodoItemRecord $record): TodoItemInterface
    {
        return new TodoItem(
            TodoItemId::of((int) $record->id),
            Title::of($record->title),
            CompletedAt::of(isset($record->completed_at) ? new Carbon($record->completed_at) : null)
        );
    }

    /**
     * 配列から作成
     * @param array $record
     * @return TodoItem
     */
    public function fromArray(array $record): TodoItem
    {
        return new TodoItem(
            TodoItemId::of(isset($record['id']) ? (int) $record['id'] : null),
            Title::of($record['title']),
            CompletedAt::of(isset($record['completed_at']) ? new Carbon($record['completed_at']) : null)
        );
    }
}
