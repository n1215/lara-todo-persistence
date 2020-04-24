<?php
declare(strict_types=1);

namespace N1215\LaraTodo\Impls\POPOAndAtlas\TodoItem;

use Atlas\Mapper\RecordSet;

/**
 * @method TodoItemRecord offsetGet($offset)
 * @method TodoItemRecord appendNew(array $fields = [])
 * @method TodoItemRecord|null getOneBy(array $whereEquals)
 * @method TodoItemRecordSet getAllBy(array $whereEquals)
 * @method TodoItemRecord|null detachOneBy(array $whereEquals)
 * @method TodoItemRecordSet detachAllBy(array $whereEquals)
 * @method TodoItemRecordSet detachAll()
 * @method TodoItemRecordSet detachDeleted()
 */
class TodoItemRecordSet extends RecordSet
{
}
