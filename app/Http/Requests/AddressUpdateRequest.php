<?php

namespace App\Http\Requests;

use App\Models\Address;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class AddressUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        // $userId = Auth::id();

        return [
            'street' => Address::VALIDATION_RULE_STREET,
            'number' => [Address::VALIDATION_RULE_NBR],
            'box' => [Address::VALIDATION_RULE_BOX],
            'province' => [Address::VALIDATION_RULE_PROVINCE],
            'city' => Address::VALIDATION_RULE_CITY,
            'country' => Address::VALIDATION_RULE_COUNTRY,
            'postal_code' => Address::VALIDATION_RULE_POSTAL_CODE,
            'type' => Address::VALIDATION_RULE_TYPE,
            // 'is_landlord' => '',
        ];

    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return array_merge(
            Address::VALIDATION_MESSAGES);
    }

}
