<?php
declare(strict_types=1);

namespace N1215\LaraTodo\Impls\POPOAndAtlas\TodoItem;

use Atlas\Mapper\Record;

/**
 * @method TodoItemRow getRow()
 */
class TodoItemRecord extends Record
{
    use TodoItemFields;
}
