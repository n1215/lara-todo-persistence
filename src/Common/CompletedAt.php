<?php

declare(strict_types=1);

namespace N1215\LaraTodo\Common;

use Carbon\Carbon;
use Carbon\CarbonInterface;

/**
 * Todo項目の完了日時
 * @package N1215\LaraTodo\Common
 */
final class CompletedAt
{
    /**
     * @var CarbonInterface|null
     */
    private $value;

    /**
     * コンストラクタ
     * @param CarbonInterface|null $value
     */
    private function __construct(?CarbonInterface $value)
    {
        $this->value = $value;
    }

    /**
     * 値を取得
     * @return CarbonInterface|null
     */
    public function getValue(): ?CarbonInterface
    {
        return $this->value;
    }

    /**
     * 指定した値より過去かどうか
     * @param CarbonInterface|null $datetime
     * @return bool
     */
    public function isPast(?CarbonInterface $datetime): bool
    {
        if ($datetime === null) {
            $datetime = Carbon::now();
        }

        if ($this->value === null) {
            return false;
        }

        return $this->value->lte($datetime);
    }

    /**
     * @param CarbonInterface|null $value
     * @return CompletedAt
     */
    public static function of(?CarbonInterface $value): self
    {
        return new self($value);
    }
}
