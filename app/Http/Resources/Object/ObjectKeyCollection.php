<?php

declare(strict_types=1);

namespace App\Http\Resources\Object;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * ObjectKeyCollection class
 *
 * @since Aug 07, 2025
 * @author Greg Malahito <mgmalahito@gmail.com>
 */
class ObjectKeyCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
             'data' => $this->collection,
             'meta' => [
                 'total' => $this->collection->count(),
                 'timestamp' => now()->toISOString(),
             ],
         ];
    }
}
