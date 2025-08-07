<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\ObjectKey;
use App\DTOs\ObjectKeyStoreDTO;
use Illuminate\Support\Collection;

/**
 * Interface for ObjectKey repository
 *
 * @since Aug 07, 2025
 * @author Greg Malahito <mgmalahito@gmail.com>
 */
interface ObjectKeyRepositoryInterface
{
    public function get(string $order = 'asc'): Collection;

    public function store(ObjectKeyStoreDTO $dto): ObjectKey;

    public function findByKey(string $objectKey, ?int $timestamp = null): ObjectKey;


}
