<?php

namespace App\Http\Requests;

use App\DocumentTypeEnum as AppDocumentTypeEnum;
use App\GenderEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProfileRoleRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('create', \App\Models\ProfileRole::class);
    }

    public function rules()
    {
        return [
            'first_name'    => 'required|string|max:30',
            'mid_name'      => 'required|string|max:30',
            'last_name'     => 'required|string|max:30',
            'date_of_birth' => 'required|date|before:today',
            'nationality_id'=> 'required|exists:countries,id',

            'national_no'   => [
                Rule::requiredIf(fn () => $this->input('nationality_id') == 1),
                'nullable',
                'regex:/^[1-2][0-9]{11}$/',
                'size:12',
                'unique:profile_roles,national_no',
            ],

            'document_type' => 'required|integer|in:' . implode(',', array_column(AppDocumentTypeEnum::cases(), 'value')),
            'document_no'   => 'required|string|max:255',
            'IBAN'          => 'required|string|max:255|unique:profile_roles,IBAN',
            'document_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'gender' => [
                'sometimes',
                'integer',
                Rule::in(array_column(GenderEnum::cases(), 'value')),
],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->input('nationality_id') == 1) {
                $nationalNo = $this->input('national_no');
                if ($nationalNo) {
                    $firstDigit = substr($nationalNo, 0, 1);

                    if (!in_array($firstDigit, ['1', '2'])) {
                        $validator->errors()->add('national_no', __('National No first digit must be 1 or 2.'));
                    }
                }
            }
        });
    }

    public function passedValidation()
    {
        if ($this->input('nationality_id') == 1 && $this->filled('national_no')) {
            // Libyan national → gender auto-detected from national_no
            $firstDigit = substr($this->input('national_no'), 0, 1);

            $gender = $firstDigit === '1'
                ? GenderEnum::Male->value
                : GenderEnum::Female->value;

            $this->merge(['gender' => $gender]);
        } else {
            // Non-Libyan → use submitted gender
            $this->merge(['gender' => $this->input('gender')]);
        }
    }

    public function messages()
    {
        return [
            'first_name.required'    => __('First Name is required and max 30 chars'),
            'mid_name.required'      => __('Middle Name is required and max 30 chars'),
            'last_name.required'     => __('Last Name is required and max 30 chars'),
            'date_of_birth.required' => __('Date of Birth is required and must be before today'),
            'nationality_id.required'=> __('Nationality is required'),
            'national_no.required'   => __('National No must start with 1 or 2 and be 12 digits (required for Libyans)'),
            'national_no.regex'      => __('National No must start with 1 or 2 and be 12 digits'),
            'national_no.size'       => __('National No must be exactly 12 digits'),
            'document_type.required' => __('Document Type is required'),
            'document_no.required'   => __('Document No is required'),
            'IBAN.required'          => __('IBAN is required'),
            'document_file.required' => __('Document File is required'),
            'document_file.mimes'    => __('Document File must be a file of type: pdf, jpg, jpeg, png'),
            'document_file.max'      => __('Document File size must not exceed 2MB'),
            'gender.required'        => __('Gender is required'),
            'gender.enum'            => __('Gender must be a valid value'),
        ];
    }
}
