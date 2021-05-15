<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MovementCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'amount'        => 'required|integer|min:50',
            'bill_100000'   => 'nullable|integer|min:0',
            'bill_50000'    => 'nullable|integer|min:0',
            'bill_20000'    => 'nullable|integer|min:0',
            'bill_10000'    => 'nullable|integer|min:0',
            'bill_5000'     => 'nullable|integer|min:0',
            'bill_2000'     => 'nullable|integer|min:0',
            'bill_1000'     => 'nullable|integer|min:0',
            'coin_1000'     => 'nullable|integer|min:0',
            'coin_500'      => 'nullable|integer|min:0',
            'coin_200'      => 'nullable|integer|min:0',
            'coin_100'      => 'nullable|integer|min:0',
            'coin_50'       => 'nullable|integer|min:0',
        ];
    }
}
