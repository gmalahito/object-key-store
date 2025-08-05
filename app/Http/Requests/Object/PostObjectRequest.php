<?php

declare(strict_types=1);

namespace App\Http\Requests\Object;

use Illuminate\Foundation\Http\FormRequest;

/**
 * PostObjectRequest class
 *
 * @since Aug 05, 2025
 * @author Greg Malahito <mgmalahito@gmail.com>
 */
final class PostObjectRequest extends FormRequest
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
            //
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $data = $this->json()->all();

            // Must have exactly one key-value pair
            if (count($data) !== 1) {
                $validator->errors()->add('body', 'JSON must contain exactly one key-value pair.');
                return;
            }

            // Optional: validate key format (e.g., no spaces)
            $key = array_key_first($data);

            if (!preg_match('/^[a-zA-Z0-9_\-:.]+$/', $key)) {
                $validator->errors()->add('key', 'Key must be alphanumeric or contain _ - : .');
            }

            // Optional: validate value type (string or JSON object/array)
            $value = $data[$key];

            if (!is_string($value) && !is_array($value)) {
                $validator->errors()->add('value', 'Value must be a string/blob.');
            }
        });
    }
}
