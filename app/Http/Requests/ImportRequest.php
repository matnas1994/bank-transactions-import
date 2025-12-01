<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportRequest extends FormRequest
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
            'file' => 'required|file|mimetypes:text/csv,application/json,text/xml,application/xml,text/plain',
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'Musisz wybrać plik do importu.',
            'file.file' => 'Wybrany plik jest niepoprawny.',
            'file.mimetypes' => 'Obsługiwane typy plików: CSV, JSON, XML.',
            'file.max' => 'Plik jest za duży (max 10 MB).',
        ];
    }
}
