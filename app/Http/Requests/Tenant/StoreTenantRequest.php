<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class StoreTenantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hostel_id' => ['required', 'integer', 'exists:hostels,id'],
            'room_id' => ['nullable', 'integer', 'exists:rooms,id'],
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:150'],
            'id_card' => ['required', 'string', 'max:50', 'unique:tenants,id_card'],
            'address' => ['nullable', 'string', 'max:255'],
        ];
    }
}
