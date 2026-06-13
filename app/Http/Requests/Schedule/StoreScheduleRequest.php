<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;

class StoreScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Source: jadwal_controller.js — create/update
     * Used for both create and update (identical rule sets).
     */
    public function rules(): array
    {
        return [
            'date'         => ['required', 'date'],
            'classroom_id' => ['required', 'integer', 'exists:classrooms,id'],
            'topic'        => ['required', 'string'],
        ];
    }
}
