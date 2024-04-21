<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Models\Address;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Auth;

class RegistrationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $isCompany = $this->input('is_company') == 'on';
        $companyNameRules = $isCompany ? 'required' : '';


        return [
            'username' => User::VALIDATION_RULE_USERNAME,
            'first_name' => [User::VALIDATION_RULE_FIRST_NAME, 'string', 'max:255'],
            'last_name' => [User::VALIDATION_RULE_LAST_NAME, 'string', 'max:255'],
            'password' => [User::VALIDATION_RULE_PASSWORD],
            'password_confirmation' => ['required', 'same:password'],
            'email' => [User::VALIDATION_RULE_EMAIL, 'email'],
            'phone_nbr' => [User::VALIDATION_RULE_PHONE_NBR],
            'birth_date' => array_merge(User::VALIDATION_RULE_BIRTHDATE, ['date']),
            'company_name' => 'required_if:is_company,1',
            
            'street' => [Address::VALIDATION_RULE_STREET],
            'number' => [Address::VALIDATION_RULE_NBR],
            'box' => [Address::VALIDATION_RULE_BOX],
            'province' => [Address::VALIDATION_RULE_PROVINCE],
            'city' => [Address::VALIDATION_RULE_CITY],
            'country' => [Address::VALIDATION_RULE_COUNTRY],
            'postal_code' => [Address::VALIDATION_RULE_POSTAL_CODE],
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
            User::VALIDATION_MESSAGES, Address::VALIDATION_MESSAGES,[
            'username.string' => 'Wrong type. It needs to be a string',
            'username.max' => 'The string is to long',

            'FirstName.string' => 'Wrong type. It needs to be a string',
            'FirstName.max' => 'The string is to long',

            'LastName.string' => 'Wrong type. It needs to be a string',
            'LastName.max' => 'The string is to long',

            'password_confirmation.required' => 'Confirm Password is required',
            'password_confirmation.same' => 'Password and Confirm Password are not the same',

            'email.email' => 'Wrong type. It needs to be an email form',

            'BirthDate.date' => 'Wrong type. It needs to be a date',

            'company_name.required' => 'CompanyName is required',
            ]
        );
    }
}
