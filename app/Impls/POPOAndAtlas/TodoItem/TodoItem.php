<?php
declare(strict_types=1);

namespace N1215\LaraTodo\Impls\POPOAndAtlas\TodoItem;

use Atlas\Mapper\Mapper;
use Atlas\Table\Row;

/**
 * @method TodoItemTable getTable()
 * @method TodoItemRelationships getRelationships()
 * @method TodoItemRecord|null fetchRecord($primaryVal, array $with = [])
 * @method TodoItemRecord|null fetchRecordBy(array $whereEquals, array $with = [])
 * @method TodoItemRecord[] fetchRecords(array $primaryVals, array $with = [])
 * @method TodoItemRecord[] fetchRecordsBy(array $whereEquals, array $with = [])
 * @method TodoItemRecordSet fetchRecordSet(array $primaryVals, array $with = [])
 * @method TodoItemRecordSet fetchRecordSetBy(array $whereEquals, array $with = [])
 * @method TodoItemSelect select(array $whereEquals = [])
 * @method TodoItemRecord newRecord(array $fields = [])
 * @method TodoItemRecord[] newRecords(array $fieldSets)
 * @method TodoItemRecordSet newRecordSet(array $records = [])
 * @method TodoItemRecord turnRowIntoRecord(Row $row, array $with = [])
 * @method TodoItemRecord[] turnRowsIntoRecords(array $rows, array $with = [])
 */
class TodoItem extends Mapper
{
}
