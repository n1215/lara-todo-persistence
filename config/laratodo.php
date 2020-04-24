<?php

return [
    /**
     * 実装クラスの切り替え 1〜6
     * 1 => Impls\EloquentAsEntity\TodoItemRepository::class
     * 2 => Impls\EntityContainsEloquent\TodoItemRepository::class
     * 3 => Impls\POPOAndEloquent\TodoItemRepository::class
     * 4 => Impls\POPOAndQueryBuilder\TodoItemRepository::class
     * 5 => Impls\POPOAndPDO\TodoItemRepository::class
     * 6 => Impls\POPOAndAtlas\TodoItemRepository::class
     */
    'impl' => 1,
];
