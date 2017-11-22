<?php
declare(strict_types=1);

namespace N1215\LaraTodo\Impls\POPOAndAtlas;

use Atlas\Orm\Mapper\Record;

/**
 * Todo項目のレコード
 * @package N1215\LaraTodo\Impls\POPOAndAtlas
 *
 * @property int $id|null Todo項目のID
 * @property string $title Todo項目のタイトル
 * @property string $completed_at 完了日時
 * @property string $created_at 作成日時
 * @property string $updated_at 更新日時
 */
class TodoItemRecord extends Record
{
}
