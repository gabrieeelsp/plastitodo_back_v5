<?php

namespace App\Http\Requests\v1\tags;

use Illuminate\Foundation\Http\FormRequest;

class CreateTagRequest extends FormRequest
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
            'data' => 'required|array',
            'data.type' => 'required|in:tags',
            'data.attributes' => 'required|array',
            'data.attributes.name' => 'required|string|max:100',
            'data.attributes.color' => 'required|string|max:100',


        ];
    }

    public function messages()
    {
        return [
            'data.attributes.name.required' => 'The name field is required.',
            'data.attributes.name.max' => 'The name field should be less than 100.', 
            'data.attributes.color.required' => 'The color field is required.',

        ];
    }
}
