<?php

namespace App\Http\Requests\Faculty;

use Illuminate\Foundation\Http\FormRequest;

class StoreFacultyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Source: fakultas_controller.js — create/update
     * Used for both create and update (identical rule sets).
     */
    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'thumbnail'   => ['nullable', 'image', 'max:2048'],
        ];
    }
}
