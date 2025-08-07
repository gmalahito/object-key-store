<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Object;

use Illuminate\Http\Request;
use App\DTOs\ObjectKeyFilterDTO;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Contracts\ObjectKeyServiceInterface;
use App\Http\Resources\Object\ObjectKeyValueOnlyResource;

/**
 * ShowObjectController class
 *
 * @since Aug 01, 2025 (Sprint: Jack)
 * @author Greg Malahito <mgmalahito@gmail.com>
 */
final class ShowObjectController extends Controller
{
    public function __construct(
        private readonly ObjectKeyServiceInterface $objectKeyService
    ) {
    }

    /**
     * Handle the request to show a specific object by its key.
     * It can accept an optional query parameter to specify a timestamp.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string                   $myKey
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request, string $myKey): JsonResponse
    {
        $dto       = ObjectKeyFilterDTO::fromRequest($request->all(), $myKey);
        $objectKey = $this->objectKeyService->getObjectByKey($dto);

        return response()->json(
            [
                'data' => new ObjectKeyValueOnlyResource($objectKey),
            ],
            200
        )->header('Content-Type', 'application/json');

    }
}
