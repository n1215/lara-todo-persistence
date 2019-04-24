<?php
declare(strict_types=1);

namespace N1215\LaraTodo\Impls\POPOAndAtlas\TodoItem;

use Atlas\Query\Delete;
use Atlas\Query\Insert;
use Atlas\Query\Select;
use Atlas\Query\Update;
use Atlas\Table\Row;
use Atlas\Table\Table;
use Atlas\Table\TableEvents;
use PDOStatement;

class TodoItemTableEvents extends TableEvents
{
}
