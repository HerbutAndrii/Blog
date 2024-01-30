<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TagRequest extends FormRequest
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
            'tag_name' => ['required', 'string', 'unique:tags,name']
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'tag_name' => strtolower(str_replace(' ', '_', $this->tag_name))
        ]);

        if(! empty($this->tag_name) && $this->tag_name[0] != '#') {
            $this->merge([
                'tag_name' => '#' . $this->tag_name
            ]);
        }
    }
}
