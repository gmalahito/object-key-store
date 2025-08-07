<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Models\ObjectKey;
use App\DTOs\ObjectKeyStoreDTO;
use App\DTOs\ObjectKeyFilterDTO;
use Illuminate\Support\Collection;

/**
 * Object Key Service Interface
 *
 * @since Aug 07, 2025
 * @author Greg Malahito <mgmalahito@gmail.com>
 */
interface ObjectKeyServiceInterface
{
    /**
     * Get all object keys
     *
     * @param  \App\DTOs\ObjectKeyFilterDTO $dto
     * @return \Illuminate\Support\Collection
     */
    public function getAllObjectKeys(ObjectKeyFilterDTO $dto): Collection;

    /**
     * Get a specific object key by its key or filter by timestamp.
     *
     * @param  \App\DTOs\ObjectKeyFilterDTO $dto
     * @return \App\Models\ObjectKey|null
     */
    public function getObjectByKey(ObjectKeyFilterDTO $dto): ?ObjectKey;

    /**
     * Create a new object key.
     *
     * @param  \App\DTOs\ObjectKeyStoreDTO $dto
     * @return \App\Models\ObjectKey
     */
    public function createObjectKey(ObjectKeyStoreDTO $dto): ObjectKey;
}
