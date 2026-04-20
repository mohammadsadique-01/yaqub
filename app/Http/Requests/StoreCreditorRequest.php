<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCreditorRequest extends FormRequest
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
            'account_name'        => 'required|string|max:255',
            'phone'               => 'nullable|string|max:20',
            'gstin'               => 'nullable|string|max:20',

            'consignee_address'   => 'nullable|string',
            'consignee_address2'  => 'nullable|string',
            'consignee_district'  => 'nullable|string|max:100',
            'consignee_city'      => 'nullable|string|max:100',
            'consignee_state'     => 'nullable|string|max:100',

            'billing_address'     => 'nullable|string',
            'billing_city'        => 'nullable|string|max:100',
            'billing_state'       => 'nullable|string|max:100',

            'contact_person'      => 'nullable|string|max:100',
            'customer_code'       => 'nullable|string|max:100',

            'magazine_no'         => 'nullable|string|max:100',
            'thana'               => 'nullable|string|max:100',

            'licence_no'          => 'nullable|string|max:100',
            'licence_valid'       => 'nullable|date',
            'licence_type'        => 'nullable|string|max:100',

            'agreement_period'    => 'nullable|string|max:100',
            'lease_area'          => 'nullable|string|max:100',
            'lease_period'        => 'nullable|string|max:100',

            'hide_quantity'       => 'nullable|boolean',
            'remarks'             => 'nullable|string',

            'opening_amount'      => 'nullable|numeric',
            'amount_type'         => 'required|in:DR,CR',

            // Sites
            'site_at'             => 'nullable|array',
            'site_at.*'           => 'nullable|string|max:255',
        ];
    }
}
