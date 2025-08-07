<?php

declare(strict_types=1);

namespace App\DTOs;

/**
 * Data Transfer Object for storing object key-value pairs.
 *
 * @since Aug 07, 2025
 * @author Greg Malahito <mgmalahito@gmail.com>
 */
class ObjectKeyStoreDTO
{
    /**
     * Create a new ObjectKeyStoreDTO instance.
     *
     * @param  string|int $key
     * @param  string $value
     * @param  string $type
     */
    public function __construct(
        public readonly string|int $key,
        public readonly string $value,
        public readonly string $type = 'string'
    ) {
        $this->validate();
    }

    /**
     * Create an instance from request data.
     * Expects an associative array with a single key-value pair.
     *
     * @param  array $data
     * @return self
     */
    public static function fromRequest(array $data): self
    {
        $key = array_key_first($data);
        $value = $data[$key] ?? null;

        if (empty($key)) {
            throw new \InvalidArgumentException('Object key is required.');
        }

        if (empty($value)) {
            throw new \InvalidArgumentException('Object value is required.');
        }

        $type = self::detectType($value);

        return new self($key, $value, $type);
    }

    /**
     * Detect the type of the value.
     *
     * @param  string $value
     * @return string
     */
    private static function detectType(string $value): string
    {
        // Check if it's base64 encoded binary data
        if (self::isBase64($value)) {
            return 'blob';
        }

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

        // Additional check: base64 should contain some non-alphabetic characters
        // or be significantly longer than typical strings
        if (strlen($value) < 16 && ctype_alpha($value)) {
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

    /**
     * Validate the DTO properties.
     *
     * @return void
     */
    public function validate(): void
    {
        if (empty($this->key)) {
            throw new \InvalidArgumentException('Object key is required.');
        }

        if (empty($this->value)) {
            throw new \InvalidArgumentException('Object value is required.');
        }

        if (!in_array($this->type, ['string', 'blob'])) {
            throw new \InvalidArgumentException('Invalid type. Must be string or blob.');
        }
    }

    /**
     * Convert the DTO to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'key'   => $this->key,
            'value' => $this->value,
            'type'  => $this->type,
        ];
    }
}
