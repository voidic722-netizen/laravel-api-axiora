<?php

namespace App\Http\Requests\Concerns;

use Illuminate\Validation\ValidationException;

/**
 * Multipart/form-data requests (file uploads) send array/object fields as
 * JSON-encoded strings. This trait decodes them into native PHP arrays
 * before validation, mirroring the original parseJsonField()/JSON.parse()
 * calls in tugas_controller.js and ujian_controller.js.
 */
trait DecodesJsonFields
{
    /**
     * @param string[] $fields
     */
    protected function decodeJsonFields(array $fields): void
    {
        $decoded = [];

        foreach ($fields as $field) {
            $value = $this->input($field);

            if (is_string($value)) {
                $result = json_decode($value, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw ValidationException::withMessages([
                        $field => "{$field} is not valid JSON",
                    ]);
                }

                $decoded[$field] = $result;
            }
        }

        if (!empty($decoded)) {
            $this->merge($decoded);
        }
    }
}
