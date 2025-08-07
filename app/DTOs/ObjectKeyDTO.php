<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Models\ObjectKey;
use Illuminate\Http\Request;
use InvalidArgumentException;

/**
 * ObjectKeyDTO class
 *
 * @since Aug 07, 2025
 * @author Greg Malahito <mgmalahito@gmail.com>
 */
class ObjectKeyDTO
{
    public function __construct(
        public string $key,
        public string $value,
        public string $type, // Auto-detected: 'string' or 'blob'
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        $data = $request->all();
        $key = array_key_first($data);
        $value = $data[$key];

        // Only accept string values in JSON
        if (!is_string($value)) {
            throw new InvalidArgumentException(
                'Value must be a string. Arrays, objects, numbers, and booleans are not supported.'
            );
        }

        // Auto-detect if it's string or blob
        $type = self::detectValueType($value);

        return new self(
            key: $key,
            value: $value,
            type: $type,
        );
    }

    /**
     * Automatically detect if string contains binary data (blob) or is regular text
     */
    private static function detectValueType(string $value): string
    {
        // Check for common blob indicators:

        // 1. Base64 encoded data pattern
        if (self::isBase64($value)) {
            return 'blob';
        }

        // 2. Contains non-printable characters (binary data)
        if (preg_match('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', $value)) {
            return 'blob';
        }

        // 3. Not valid UTF-8
        if (!mb_check_encoding($value, 'UTF-8')) {
            return 'blob';
        }

        // 4. Very high ratio of non-ASCII characters (likely binary)
        $nonAsciiCount = preg_match_all('/[^\x20-\x7E]/', $value);

        if ($nonAsciiCount > strlen($value) * 0.3) { // More than 30% non-ASCII
            return 'blob';
        }

        // Default to string
        return 'string';
    }

    /**
     * Check if string is base64 encoded
     */
    private static function isBase64(string $value): bool
    {
        // Must be reasonable length for base64
        if (strlen($value) < 4) {
            return false;
        }

        // Must be multiple of 4 (with padding)
        if (strlen($value) % 4 !== 0) {
            return false;
        }

        // Check base64 pattern
        if (!preg_match('/^[a-zA-Z0-9+\/]*={0,2}$/', $value)) {
            return false;
        }

        // Try to decode and re-encode
        $decoded = base64_decode($value, true);
        if ($decoded === false) {
            return false;
        }

        // If re-encoding produces the same string, it's base64
        return base64_encode($decoded) === $value;
    }

    public function isBlob(): bool
    {
        return $this->type === 'blob';
    }

    /**
     * Get the raw value (decode base64 if it's blob)
     */
    public function getRawValue(): string
    {
        if ($this->isBlob() && self::isBase64($this->value)) {
            return base64_decode($this->value);
        }

        return $this->value;
    }

    public static function fromModel(ObjectKey $model): self
    {
        return new self(
            key: $model->key,
            value: $model->value,
            type: $model->type
        );
    }

    public function toArray(): array
    {
        return [$this->getValueForOutput()];
    }

    /**
     * Get value formatted for API output
     * Blobs are base64 encoded for JSON transport
     */
    public function getValueForOutput(): string
    {
        if ($this->isBlob()) {
            // Encode binary data as base64 for JSON
            return base64_encode($this->value);
        }

        return $this->value;
    }
}
