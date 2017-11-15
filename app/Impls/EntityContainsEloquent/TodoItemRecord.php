<?php
declare(strict_types=1);

namespace N1215\LaraTodo\Impls\EntityContainsEloquent;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Eloquent Model
 * @package N1215\LaraTodo\Impls\EntityContainsEloquent
 *
 * @property int|null $id Todo項目のID
 * @property string $title Todo項目のタイトル
 * @property Carbon|null $completed_at 完了日時
 * @property Carbon $created_at 作成日時
 * @property Carbon $updated_at 更新日時
 */
class TodoItemRecord extends Model
{
    protected $table = 'todo_items';

    protected $dates = ['completed_at'];
}
