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
        $userId = Auth::id();

        $customerAddresses = Customer_Address::where('user_id', $userId)->get();

        $addresses = [];

        foreach($customerAddresses as $cusadr){
            $addresses[] = Address::where('id', $cusadr->address_id)->first();
        }

        foreach($addresses as $key => $address){
            if ($key == 0){
                dd($key);
            }
        }

        //! werkt nog niet
        // foreach ($this->request->get('addresses') as $key => $address) {
        //     if ($key == 0){
        //         dd($key);
        //         return [
        //             'street'.$key => Address::VALIDATION_RULE_STREET,
        //             'number' => Address::VALIDATION_RULE_NBR,
        //             'box' => Address::VALIDATION_RULE_BOX,
        //             'postal_code' => Address::VALIDATION_RULE_POSTAL_CODE,
        //             'city' => Address::VALIDATION_RULE_CITY,
        //             'province' => Address::VALIDATION_RULE_PROVINCE,
        //             'country' => Address::VALIDATION_RULE_COUNTRY,
        //             'type' => Address::VALIDATION_RULE_TYPE,
        //             // 'is_billing_address' => ['required'],
        //             //CH Je mag maar 1 billing addres hebben.
        //             // 'is_billing_address' => [
        //             //     Rule::unique('customer_addresses')->where(function ($query) use ($userId) {
        //             //         return $query->where('user_id', $userId);
        //             //     }),
        //             // ],
        //         ];


        //     }
        // }

        // return [
        //     'street' => Address::VALIDATION_RULE_STREET,
        //     'number' => Address::VALIDATION_RULE_NBR,
        //     'box' => Address::VALIDATION_RULE_BOX,
        //     'postal_code' => Address::VALIDATION_RULE_POSTAL_CODE,
        //     'city' => Address::VALIDATION_RULE_CITY,
        //     'province' => Address::VALIDATION_RULE_PROVINCE,
        //     'country' => Address::VALIDATION_RULE_COUNTRY,
        //     'type' => Address::VALIDATION_RULE_TYPE,
        //     // 'is_billing_address' => ['required'],
        //     //CH Je mag maar 1 billing addres hebben.
        //     // 'is_billing_address' => [
        //     //     Rule::unique('customer_addresses')->where(function ($query) use ($userId) {
        //     //         return $query->where('user_id', $userId);
        //     //     }),
        //     // ],
        // ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return array_merge(
            Address::VALIDATION_MESSAGES,
        );
    }

    public function Address()
    {
        return $this->only([
            'street',
            'number',
            'type',
            'box',
            'postal_code',
            'city',
            'province',
            'country',
            'is_billing_address'
        ]);
    }

}
