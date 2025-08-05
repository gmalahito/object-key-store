<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Object;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Repositories\ObjectKeyRepository;
use App\Http\Resources\Object\ObjectKeyResource;

/**
 * GetObjectController class
 *
 * @since Aug 01, 2025 (Sprint: Jack)
 *
 * @author Greg Malahito <mgmalahito@gmail.com>
 */
final class GetObjectController extends Controller
{
    /**
     * This method should return all object keys.
     * It can accept an optional query parameter to specify the order of the results.
     * The default order is ascending.
     *
     * @param  \Illuminate\Http\Request              $request
     * @param  \App\Repositories\ObjectKeyRepository $objectKeyRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request, ObjectKeyRepository $objectKeyRepository): JsonResponse
    {
        $objectKeys = $objectKeyRepository->getAllObjects($request->query('order', 'asc'));

        return ObjectKeyResource::collection($objectKeys)
            ->response()
            ->setStatusCode(200)
            ->header('Content-Type', 'application/json');
    }
}
