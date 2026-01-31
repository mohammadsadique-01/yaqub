<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDebitorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // REQUIRED
            'account_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',

            // OPTIONAL
            'gst_number' => 'nullable|string|max:20',

            'actual_address' => 'nullable|string',
            'billing_address' => 'nullable|string',

            // location (optional both ways)
            'location_id' => 'nullable|exists:locations,id',
            'state' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'state_code' => 'nullable|string|max:10',

            // VILLAGE
            'village_id' => 'nullable|exists:villages,id',
            'village_name' => [
                'nullable',
                'string',
                'max:255',
                Rule::requiredIf(! $this->filled('village_id')),
                Rule::unique('villages')->where(function ($query) {
                    return $query->where('location_id', $this->location_id);
                }),
            ],

            'lease_area' => 'nullable|string|max:100',
            'lease_period' => 'nullable|string|max:100',
            'remark' => 'nullable|string',

            'site_name' => 'nullable|array',
            'site_name.*' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'account_name.required' => 'Account name is required.',
            'phone.required' => 'Phone number is required.',

            'village_name.required' => 'Please enter village name.',
            'village_name.unique' => 'This village already exists for the selected location.',

            'location_id.exists' => 'Selected location is invalid.',
            'village_id.exists' => 'Selected village is invalid.',

            'site_name.*.required' => 'Site name cannot be empty',
        ];
    }
}
