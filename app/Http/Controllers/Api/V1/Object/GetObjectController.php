<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Object;

use Illuminate\Http\Request;
use App\DTOs\ObjectKeyFilterDTO;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Object\ObjectKeyResource;
use App\Http\Resources\Object\ObjectKeyCollection;
use App\Services\Contracts\ObjectKeyServiceInterface;
use App\Repositories\Contracts\ObjectKeyRepositoryInterface;

/**
 * GetObjectController class
 *
 * @since Aug 01, 2025 (Sprint: Jack)
 *
 * @author Greg Malahito <mgmalahito@gmail.com>
 */
final class GetObjectController extends Controller
{
    public function __construct(
        private readonly ObjectKeyServiceInterface $objectKeyService
    ) {}

    /**
     * This method should return all object keys.
     * It can accept an optional query parameter to specify the order of the results.
     * The default order is ascending.
     *
     * @param  \Illuminate\Http\Request              $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $dto        = ObjectKeyFilterDTO::fromRequest($request->all());
        $objectKeys = $this->objectKeyService->getAllObjectKeys($dto);

        return ObjectKeyCollection::make($objectKeys)
            ->response()
            ->setStatusCode(200)
            ->header('Content-Type', 'application/json');
    }
}
