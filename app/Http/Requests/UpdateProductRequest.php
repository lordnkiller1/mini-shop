<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'min:3',
                'max:100',
                Rule::unique('products', 'title')
                    ->ignore($this->route('product'))
            ],

            'category_id' => [
                'required',
                'exists:categories,id'
            ],

            'price' => [
                'required',
                'integer',
                'min:0'
            ],

            'status' => [
                'required',
                'boolean'
            ],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
