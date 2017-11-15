<?php
declare(strict_types=1);

namespace N1215\LaraTodo\Common;

/**
 * Todo項目のID
 * @package N1215\LaraTodo\Common
 */
final class TodoItemId
{
    /**
     * スカラー値
     * @var int|null
     */
    private $value;

    /**
     * コンストラクタ
     * @param int|null $value
     */
    private function __construct(?int $value)
    {
        $this->value = $value;
    }

    /**
     * スカラー値を取得
     * @return int|null
     */
    public function getValue(): ?int
    {
        return $this->value;
    }

    /**
     * @param int|null $value
     * @return TodoItemId
     */
    public static function of(?int $value): self
    {
        return new self($value);
    }
}
