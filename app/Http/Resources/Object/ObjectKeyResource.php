<?php

declare(strict_types=1);

namespace App\Http\Resources\Object;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * ObjectKeyResource class
 *
 * @since Aug 05, 2025
 * @author Greg Malahito <mgmalahito@gmail.com>
 */
final class ObjectKeyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'key'        => $this->resource->key,
            'value'      => $this->resource->value,
            'type'       => $this->resource->type,
            'created_at' => $this->resource->created_at,
        ];
    }
}
