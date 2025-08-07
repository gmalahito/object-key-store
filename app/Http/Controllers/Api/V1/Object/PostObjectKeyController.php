<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Object;

use Illuminate\Http\Request;
use App\DTOs\ObjectKeyStoreDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Object\PostObjectRequest;
use App\Http\Resources\Object\ObjectKeyResource;
use App\Services\Contracts\ObjectKeyServiceInterface;
use App\Repositories\Contracts\ObjectKeyRepositoryInterface;

/**
 * PostObjectKeyController class
 *
 * @since Aug 01, 2025 (Sprint: Jack)
 * @author Greg Malahito <mgmalahito@gmail.com>
 */
final class PostObjectKeyController extends Controller
{
    public function __construct(
        private readonly ObjectKeyServiceInterface $objectKeyService
    ) {}

    /**
     * Handle the request to create a new object
     *
     * @param  \App\Http\Requests\Object\PostObjectRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(PostObjectRequest $request): JsonResponse
    {
        try {
            $dto       = ObjectKeyStoreDTO::fromRequest($request->all());
            $objectKey = $this->objectKeyService->createObjectKey($dto);

            return response()->json([
                'message' => 'Object created successfully',
                'data'    => new ObjectKeyResource($objectKey)
            ], 201);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        } catch (\Throwable $th) {
            Log::info(sprintf(
                'Error on: %s. Line %s. Message: %s. Trace: %s',
                __FUNCTION__,
                __LINE__,
                $th->getMessage(),
                $th->getTraceAsString()
            ));

            return response()->json(['error' => 'An error occurred while creating the object'], 400);
        }
    }
}
