<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShipmentRequest extends FormRequest
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
            'operationno' => ['required', 'integer', 'min:1'],
            'shippmintno' => ['required', 'string', 'max:255'],
            'shipgroupno' => ['required', 'exists:shipgroup,id'],
            'datano' => ['nullable', 'integer', 'min:1', 'exists:data,datano'],
            'pillno' => ['required', 'string', 'max:255'],
            'pakingno' => ['nullable', 'string', 'max:255'],
            'pilno' => ['required', 'string', 'max:255'],
            'orginalno' => ['nullable', 'string', 'max:255'],
            'pillno2' => ['nullable', 'string', 'max:255'],
            'pakingno2' => ['nullable', 'string', 'max:255'],
            'pilno2' => ['nullable', 'string', 'max:255'],
            'orginalno2' => ['nullable', 'string', 'max:255'],
            'paperno' => ['nullable', 'integer', 'min:0'],
            'others' => ['nullable', 'string'],
            'shipmtype' => ['required', 'integer', 'min:0'],
            'departmentno' => ['required', 'exists:departement,id'],
            'sectionno' => ['nullable', 'exists:section,id'],
            'sendingdate' => ['nullable', 'date'],
            'officedate' => ['nullable', 'date'],
            'workerdate' => ['nullable', 'date'],
            'workername' => ['nullable', 'string', 'max:255'],
            'state' => ['required', 'integer', 'min:0'],
            'dategase' => ['nullable', 'date'],
            'park20' => ['nullable', 'integer', 'min:0'],
            'park40' => ['nullable', 'integer', 'min:0'],
            'dectype' => ['nullable', 'string', 'max:255'],
            'shippingno' => ['nullable', 'exists:shipping_line,id'],
            'contatty' => ['nullable', 'integer', 'min:0'],
            'value' => ['nullable', 'numeric', 'min:0'],
            'relayname' => ['nullable', 'string', 'max:255'],
            'relaydate' => ['nullable', 'date'],
            'relaycases' => ['nullable', 'string'],
            'alarm' => ['nullable', 'integer', 'min:0'],
            'returndate' => ['nullable', 'date'],
            'bill_of_lading' => ['nullable', 'array'],
            'bill_of_lading.*' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }

    public function attributes(): array
    {
        return [
            'pillno' => 'رقم البوليصة',
            'pilno' => 'رقم الحاوية',
            'operationno' => 'رقم العملية',
            'shippmintno' => 'اسم الشحنة',
        ];
    }
}
