<?php

declare(strict_types=1);

namespace App\Http\Resources\Object;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * ObjectKeyValueOnlyResource class
 *
 * @since Aug 05, 2025
 * @author Greg Malahito <mgmalahito@gmail.com>
 */
final class ObjectKeyValueOnlyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<int, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            $this->resource->value,
        ];
    }
}
