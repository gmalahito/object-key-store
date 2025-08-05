<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ObjectKey;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * ObjectKeyRepository class
 *
 * @since Aug 01, 2025
 * @author Greg Malahito <mgmalahito@gmail.com>
 */
final class ObjectKeyRepository
{
    /**
     * This method should return all objects from the repository.
     *
     * @param  string                         $order
     * @return \Illuminate\Support\Collection
     */
    public function getAllObjects(string $order = 'asc'): Collection
    {
        return ObjectKey::orderBy('id', $order)->get();
    }

    /**
     * This method should return a specific object by its key or filter by timestamp.
     *
     * @param  int                        $objectKey
     * @param  int|null                   $timestamp
     * @return \App\Models\ObjectKey|null
     */
    public function findObjectByKey(string $objectKey, ?int $timestamp = null): ?ObjectKey
    {
        $objectKey = ObjectKey::where('key', $objectKey);

        if ($timestamp) {
            $date = Carbon::createFromTimestamp($timestamp);

            $objectKey->where('created_at', '<=', $date);
        }

        return $objectKey->first();
    }

    /**
     * This method should add a new object key record.
     *
     * @param  array                 $data
     * @return \App\Models\ObjectKey
     */
    public function addObject(array $data): ObjectKey
    {
        if (empty($data['key'])) {
            throw new \InvalidArgumentException('Object key is required.');
        }

        if (empty($data['value'])) {
            throw new \InvalidArgumentException('Object value is required.');
        }

        $objectKey        = new ObjectKey();
        $objectKey->key   = $data['key'];
        $objectKey->value = $data['value'];
        $objectKey->save();

        return $objectKey;
    }
}
