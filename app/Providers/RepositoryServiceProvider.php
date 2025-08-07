<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\ObjectKeyService;
use Illuminate\Support\ServiceProvider;
use App\Repositories\ObjectKeyRepository;
use App\Services\Contracts\ObjectKeyServiceInterface;
use App\Repositories\Contracts\ObjectKeyRepositoryInterface;

/**
 * Repository Service Provider
 *
 * @since Aug 07, 2025
 * @author Greg Malahito <mgmalahito@gmail.com>
 */
class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Repositories
        $this->app->bind(ObjectKeyRepositoryInterface::class, ObjectKeyRepository::class);

        // Services
        $this->app->bind(ObjectKeyServiceInterface::class, ObjectKeyService::class);
    }
}
