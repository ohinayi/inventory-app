<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Item;
use Barryvdh\Debugbar\Facades\Debugbar;

class AvailableQuantityRule implements ValidationRule
{
    protected $itemId;
    // protected $currentConsumption;

    public function __construct($itemId)
    {
        $this->itemId = $itemId;
        // $this->currentConsumption = $currentConsumption;
       
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
        $item = Item::find($this->itemId);
        // dump($value);
        $willFinish = ($item->quantity - $value) < 0;
        // dd($this->currentConsumption, $item);

        
        if ($willFinish) {
            $fail('The requested quantity exceeds the available quantity for this item.');
        }
    }
}