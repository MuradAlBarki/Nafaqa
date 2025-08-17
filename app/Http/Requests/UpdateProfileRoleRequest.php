<?php

namespace App\Http\Requests;

use App\DocumentTypeEnum as AppDocumentTypeEnum;
use App\GenderEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRoleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $profileRole = $this->route('profile_role');
        $profileRoleId = $profileRole ? $profileRole->id : null;

        return [
            'first_name'     => 'sometimes|string|max:30',
            'mid_name'       => 'sometimes|nullable|string|max:30',
            'last_name'      => 'sometimes|string|max:30',
            'date_of_birth'  => 'sometimes|date|before:today',
            'nationality_id' => 'sometimes|exists:countries,id',

            'national_no' => [
                Rule::requiredIf(fn () => $this->input('nationality_id') == 1),
                'nullable',
                'regex:/^[1-2][0-9]{11}$/',
                'size:12',
                Rule::unique('profile_roles')->ignore($profileRoleId),
            ],

            'document_type' => [
                'sometimes',
                'integer',
                Rule::in(array_column(AppDocumentTypeEnum::cases(), 'value')),
            ],

            'document_no' => [
                'sometimes',
                'string',
                'max:255',
            ],

            'IBAN' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('profile_roles')->ignore($profileRoleId),
            ],

            'document_file' => 'sometimes|file|mimes:pdf,jpg,jpeg,png|max:2048',

        'gender' => [
            'sometimes',
            Rule::in(array_column(GenderEnum::cases(), 'value')),
        ],

        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->input('nationality_id') == 1 && $this->filled('national_no')) {
                $firstDigit = substr($this->input('national_no'), 0, 1);

                if (!in_array($firstDigit, ['1', '2'])) {
                    $validator->errors()->add('national_no', __('National No first digit must be 1 or 2.'));
                }
            }
        });
    }

public function passedValidation()
{
    // Libyan nationals with national_no filled
    if ($this->input('nationality_id') == 1 && $this->filled('national_no')) {
        $nationalNo = $this->input('national_no');
        $firstDigit = substr($nationalNo, 0, 1);

        $gender = match($firstDigit) {
            '1' => GenderEnum::Male->value,
            '2' => GenderEnum::Female->value,
            default => null, // optional: keep null if invalid
        };

        // Force gender based on national_no
        if ($gender !== null) {
            $this->merge(['gender' => $gender]);
        }
    }
}



    public function messages()
    {
        return [
            'first_name.max'     => __('First Name max length is 30 characters'),
            'mid_name.max'       => __('Middle Name max length is 30 characters'),
            'last_name.max'      => __('Last Name max length is 30 characters'),
            'date_of_birth.before' => __('Date of Birth must be before today'),
            'nationality_id.exists' => __('Selected Nationality does not exist'),
            'national_no.required'  => __('National No is required for Libyan nationals'),
            'national_no.regex'     => __('National No must start with 1 or 2 and be 12 digits'),
            'national_no.size'      => __('National No must be exactly 12 digits'),
            'national_no.unique'    => __('National No must be unique'),
            'document_type.in'      => __('Document Type is invalid'),
            'document_no.max'       => __('Document No max length is 255 characters'),
            'IBAN.max'              => __('IBAN max length is 255 characters'),
            'IBAN.unique'           => __('IBAN must be unique'),
            'document_file.mimes'   => __('Document File must be a file of type: pdf, jpg, jpeg, png'),
            'document_file.max'     => __('Document File size must not exceed 2MB'),
            'gender.enum'           => __('Gender must be a valid value'),
        ];
    }
}
