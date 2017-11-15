<?php
declare(strict_types=1);

namespace N1215\LaraTodo\Common;

use Carbon\Carbon;

/**
 * Todo項目の完了日時
 * @package N1215\LaraTodo\Common
 */
final class CompletedAt
{
    /**
     * @var Carbon|null
     */
    private $value;

    /**
     * コンストラクタ
     * @param Carbon|null $value
     */
    private function __construct(?Carbon $value)
    {
        $this->value = $value;
    }

    /**
     * 値を取得
     * @return Carbon|null
     */
    public function getValue(): ?Carbon
    {
        return $this->value;
    }

    /**
     * 指定した値より過去かどうか
     * @param Carbon|null $datetime
     * @return bool
     */
    public function isPast(?Carbon $datetime): bool
    {
        if (is_null($datetime)) {
            $datetime = Carbon::now();
        }

        if (is_null($this->value)) {
            return false;
        }

        return $this->value->lte($datetime);
    }

    /**
     * @param Carbon|null $value
     * @return CompletedAt
     */
    public static function of(?Carbon $value): self
    {
        return new self($value);
    }
}
