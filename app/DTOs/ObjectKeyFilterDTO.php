<?php

declare(strict_types=1);

namespace App\DTOs;

/**
 * Object Key Filter Data Transfer Object
 *
 * @since Aug 07, 2025
 * @author Greg Malahito <mgmalahito@gmail.com>
 */
final readonly class ObjectKeyFilterDTO
{
    public function __construct(
        public ?string $key = null,
        public ?int $timestamp = null,
        public string $order = 'asc'
    ) {}

    /**
     * Create an instance from a request array.
     *
     * @param  array       $request
     * @param  string|null $key
     * @return self
     */
    public static function fromRequest(array $request, ?string $key = null): self
    {
        return new self(
            key: $key,
            timestamp: isset($request['timestamp']) ? (int) $request['timestamp'] : null,
            order: $request['order'] ?? 'asc'
        );
    }

    /**
     * Check if the filter has a timestamp.
     *
     * @return bool
     */
    public function hasTimestamp(): bool
    {
        return $this->timestamp !== null;
    }

    /**
     * Convert the DTO to an array representation.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'timestamp' => $this->timestamp,
            'order' => $this->order,
        ];
    }
}
