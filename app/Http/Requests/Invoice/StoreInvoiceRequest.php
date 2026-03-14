<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'contract_id' => ['required', 'integer', 'exists:contracts,id'],
            'billing_month' => ['required', 'string', 'size:7'],
            'due_date' => ['required', 'date'],
            'rent' => ['required', 'numeric', 'min:0'],
            'electricity_previous' => ['required', 'integer', 'min:0'],
            'electricity_current' => ['required', 'integer', 'gte:electricity_previous'],
            'electricity_price' => ['required', 'numeric', 'min:0'],
            'water_previous' => ['required', 'integer', 'min:0'],
            'water_current' => ['required', 'integer', 'gte:water_previous'],
            'water_price' => ['required', 'numeric', 'min:0'],
            'service_fee' => ['required', 'numeric', 'min:0'],
        ];
    }
}
