<?php

namespace App\Http\Requests;

use App\Rules\AvailableQuantityRule;
use App\Rules\ExceedLimitRule;
use Illuminate\Foundation\Http\FormRequest;

class ConsumptionRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()!=null;
        // dump(auth()->user()?->user);
        // return auth()->user()?->user()->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // dd($this->item_id);
        $userId = auth()->id();
        return [
            'item_id'=> 'required|exists:items,id',
            'quantity' => [
                    'required',
                    'numeric',
                    new ExceedLimitRule($userId, $this->item_id, now()),
                    new AvailableQuantityRule($this->item_id),
                ]
        ];
    }
}
