<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ObjectKey;
use App\DTOs\ObjectKeyStoreDTO;
use App\DTOs\ObjectKeyFilterDTO;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use App\Services\Contracts\ObjectKeyServiceInterface;
use App\Repositories\Contracts\ObjectKeyRepositoryInterface;

/**
 * Object Key Service
 *
 * @since Aug 07, 2025
 * @author Greg Malahito <mgmalahito@gmail.com>
 */
class ObjectKeyService implements ObjectKeyServiceInterface
{
    public function __construct(
        private readonly ObjectKeyRepositoryInterface $objectKeyRepository
    ) {}

    /** @inheritDoc */
    public function getAllObjectKeys(ObjectKeyFilterDTO $dto): Collection
    {
        return $this->objectKeyRepository->get($dto->order);
    }

    /** @inheritDoc */
    public function getObjectByKey(ObjectKeyFilterDTO $dto): ?ObjectKey
    {
        if (!$dto->key) {
            throw new \InvalidArgumentException('Key is required');
        }

        $cacheKey = "object_keys:" . md5(serialize($dto->toArray()));

        return Cache::remember($cacheKey, 300, fn() => $this->objectKeyRepository->findByKey($dto->key, $dto->timestamp));
    }

    /** @inheritDoc */
    public function createObjectKey(ObjectKeyStoreDTO $dto): ObjectKey
    {
        $dto->validate();

        $objectKey = $this->objectKeyRepository->store($dto);

        return $objectKey;
    }
}
