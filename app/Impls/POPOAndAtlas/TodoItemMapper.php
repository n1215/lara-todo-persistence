<?php
declare(strict_types=1);

namespace N1215\LaraTodo\Impls\POPOAndAtlas;

use Atlas\Orm\Mapper\AbstractMapper;

/**
 * @inheritdoc
 */
class TodoItemMapper extends AbstractMapper
{
    /**
     * @inheritdoc
     */
    protected function setRelated()
    {
        // no related fields
    }
}
