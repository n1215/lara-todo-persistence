<?php
declare(strict_types=1);

namespace N1215\LaraTodo\Common;

use Carbon\Carbon;

/**
 * Todo項目エンティティのインターフェース
 * @package N1215\LaraTodo\Common
 */
interface TodoItemInterface
{
    /**
     * IDを取得
     * @return TodoItemId
     */
    public function getId(): TodoItemId;

    /**
     * タイトルを取得
     * @return Title
     */
    public function getTitle(): Title;

    /**
     * 指定した時間までに完了しているかどうか
     * @param Carbon|null $datetime 省略した場合は現在日時を用いる
     * @return bool
     */
    public function isCompleted(?Carbon $datetime = null): bool;

    /**
     * 指定した時間で完了済みとする
     * @param Carbon|null $datetime 省略した場合は現在日時を用いる
     * @return void
     */
    public function markAsCompleted(?Carbon $datetime = null): void;
}
