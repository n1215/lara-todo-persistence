<?php
declare(strict_types=1);

namespace N1215\LaraTodo\Impls\POPOAndAtlas;

use Atlas\Orm\Table\AbstractTable;

/**
 * @inheritdoc
 */
class TodoItemTable extends AbstractTable
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'todo_items';
    }

    /**
     * @inheritdoc
     */
    public function getColNames()
    {
        return [
            'id',
            'title',
            'completed_at',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * @inheritdoc
     */
    public function getCols()
    {
        return [
            'id' => (object) [
                'name' => 'id',
                'type' => 'integer',
                'size' => null,
                'scale' => null,
                'notnull' => true,
                'default' => null,
                'autoinc' => true,
                'primary' => true,
            ],
            'title' => (object) [
                'name' => 'title',
                'type' => 'varchar',
                'size' => null,
                'scale' => null,
                'notnull' => true,
                'default' => null,
                'autoinc' => false,
                'primary' => false,
            ],
            'completed_at' => (object) [
                'name' => 'completed_at',
                'type' => 'datetime',
                'size' => null,
                'scale' => null,
                'notnull' => false,
                'default' => null,
                'autoinc' => false,
                'primary' => false,
            ],
            'created_at' => (object) [
                'name' => 'created_at',
                'type' => 'datetime',
                'size' => null,
                'scale' => null,
                'notnull' => false,
                'default' => null,
                'autoinc' => false,
                'primary' => false,
            ],
            'updated_at' => (object) [
                'name' => 'updated_at',
                'type' => 'datetime',
                'size' => null,
                'scale' => null,
                'notnull' => false,
                'default' => null,
                'autoinc' => false,
                'primary' => false,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getPrimaryKey()
    {
        return [
            'id',
        ];
    }

    /**
     * @inheritdoc
     */
    public function getAutoinc()
    {
        return 'id';
    }

    /**
     * @inheritdoc
     */
    public function getColDefaults()
    {
        return [
            'id' => null,
            'title' => null,
            'completed_at' => null,
            'created_at' => null,
            'updated_at' => null,
        ];
    }
}
