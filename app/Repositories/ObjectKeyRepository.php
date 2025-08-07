<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ObjectKey;
use Illuminate\Support\Arr;
use App\DTOs\ObjectKeyStoreDTO;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\Contracts\ObjectKeyRepositoryInterface;

/**
 * ObjectKeyRepository class
 *
 * @since Aug 01, 2025
 * @author Greg Malahito <mgmalahito@gmail.com>
 */
class ObjectKeyRepository implements ObjectKeyRepositoryInterface
{
    /**
     * This method should return all objects from the repository.
     *
     * @param  string                         $order
     * @return \Illuminate\Support\Collection
     */
    public function get(string $order = 'asc'): Collection
    {
        return ObjectKey::orderBy('id', $order)->get();
    }

    /**
     * This method should return a specific object by its key or filter by timestamp.
     *
     * @param  string                     $myKey
     * @param  int|null                   $timestamp
     * @return \App\Models\ObjectKey|null
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findByKey(string $myKey, ?int $timestamp = null): ?ObjectKey
    {
        $builder = ObjectKey::latestByKey($myKey);

        if ($timestamp) {
            $builder->createdBefore($timestamp);
        }

        $objectKey = $builder->first();

        if (!$objectKey) {
            throw new ModelNotFoundException("Object with key '{$myKey}' not found.");
        }

        return $objectKey;
    }

    /**
     * This method should add a new object key record.
     *
     * @param  ObjectKeyStoreDTO $dto
     * @return \App\Models\ObjectKey
     */
    public function store(ObjectKeyStoreDTO $dto): ObjectKey
    {
        $dto->validate();

        return ObjectKey::create($dto->toArray());
    }
}
