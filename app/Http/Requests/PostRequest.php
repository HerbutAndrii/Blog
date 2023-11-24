<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
        if($this->submit == 'post') {
            return [
                'title' => ['required', 'string'],
                'content' => ['required', 'string'],
                'preview' => ['nullable', 'file'],
                'category' => ['required', 'string'],
                'tags' => ['nullable', 'array'],
            ];  
        } elseif($this->submit == 'category') {
            return [
                'category_name' => ['required', 'string'],
            ];
        } else {
            return [
                'tag_name' => ['required', 'string'],
            ];
        }
    }

    protected function prepareForValidation(): void
    {
        if(isset($this->tag_name) && $this->tag_name[0] != '#') {
            $this->merge([
                'tag_name' => '#' . $this->tag_name,
            ]);
        }
    }
}
