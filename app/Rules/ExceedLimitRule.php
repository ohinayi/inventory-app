<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\DailyLimit;
use App\Models\Consumption;
use App\Models\Item;

class ExceedLimitRule implements ValidationRule
{
    protected $userId;
    protected $itemId;
    protected $date;

    public function __construct($userId, $itemId, $date)
    {
        $this->userId = $userId;
        $this->itemId = $itemId;
        $this->date = $date;
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $dailyLimit = DailyLimit::where('user_id', $this->userId)
            ->where('item_id', $this->itemId)
            ->first();

        $limit = $dailyLimit ? $dailyLimit->limit : Item::find($this->itemId)->default_limit;

        $currentConsumption = Consumption::where('user_id', $this->userId)
            ->where('item_id', $this->itemId)
            ->whereDate('consumed_at', $this->date)
            ->sum('quantity');

        if (($currentConsumption + $value) > $limit) {
            $fail('The consumption exceeds the daily limit for this item.');
        }
    }
}
