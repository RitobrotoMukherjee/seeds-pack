<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReturnRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->id === 1;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'billing_id' => 'required|integer',
            'return_date' => 'required|date'
        ];
    }

    public function messages()
    {
        return [
            'billing_id.required' => 'Billing Details Not Available to Return',
            'billing_id.integer' => 'Billing data currupted, cannot return',
            'return_date.required' => 'Return Date is required',
            'return_date.date' => 'Return Date must be a valid Date'
        ]
    }
}
