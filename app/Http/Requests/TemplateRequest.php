<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TemplateRequest extends FormRequest
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
            'title' => 'required|string',
            'old_url' => 'required|url',
            'new_url' => 'nullable|url',
            'size' => 'required|numeric',
            'categories' => 'required|array',
            'categories.*' => 'required|exists:categories,id',
            'image' => [
                'image',
                request()->isMethod('POST') ? 'required' : 'nullable'
            ],
        ];
    }
}
