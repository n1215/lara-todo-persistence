<?php
declare(strict_types=1);

namespace N1215\LaraTodo\Common;

/**
 * Todo項目のタイトル
 * @package N1215\LaraTodo\Common
 */
final class Title
{
    /**
     * スカラー値
     * @var string
     */
    private $value;

    /**
     * コンストラクタ
     * @param string $value
     */
    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * スカラー値を取得
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return Title
     */
    public static function of(string $value): self
    {
        return new self($value);
    }
}
