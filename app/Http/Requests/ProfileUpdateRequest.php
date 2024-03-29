<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Auth;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $user = Auth::user();
        $isCompany = $user->is_company;
        $companyNameRules = $isCompany ? 'required' : '';

        return [
            // 'name' => ['string', 'max:255'],
            // 'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],

            'username' => User::VALIDATION_RULE_USERNAME,
            'first_name' => [User::VALIDATION_RULE_FIRST_NAME, 'string', 'max:255'],
            'last_name' => [User::VALIDATION_RULE_LAST_NAME, 'string', 'max:255'],
            // CH vallidation password
            // 'password' => [User::VALIDATION_RULE_PASSWORD],
            // 'password' => User::VALIDATION_RULE_PASSWORD,
            'email' => [User::VALIDATION_RULE_EMAIL, 'email'],
            'phone_nbr' => [User::VALIDATION_RULE_PHONE_NBR],
            'birth_date' => array_merge(User::VALIDATION_RULE_BIRTHDATE, ['date']),
            'company_name' => $companyNameRules,
            // LOOK regel voor Calling
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
            User::VALIDATION_MESSAGES,[
            'username.string' => 'Wrong type. It needs to be a string',
            'username.max' => 'The string is to long',

            'FirstName.string' => 'Wrong type. It needs to be a string',
            'FirstName.max' => 'The string is to long',

            'LastName.string' => 'Wrong type. It needs to be a string',
            'LastName.max' => 'The string is to long',

            'email.email' => 'Wrong type. It needs to be an email form',

            'BirthDate.date' => 'Wrong type. It needs to be a date',

            'company_name.required' => 'CompanyName is required',
            ]
        );
    }
}
