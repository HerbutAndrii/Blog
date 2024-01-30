<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
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
        if(! isset($this->edit_content)) {
            return [
                'content' => ['required', 'string']
            ];
        } else {
            return [
                'edit_content' => ['required', 'string']
            ];
        }
    }

    public function messages(): array
    {
        return [
            'edit_content.required' => 'The content field is required.',
            'edit_content.string' => 'The content field must be a string.'
        ];
    }
}
