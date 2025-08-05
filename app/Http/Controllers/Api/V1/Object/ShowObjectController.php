<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Object;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Repositories\ObjectKeyRepository;
use App\Http\Resources\Object\ObjectKeyResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * ShowObjectController class
 *
 * @since Aug 01, 2025 (Sprint: Jack)
 * @author Greg Malahito <mgmalahito@gmail.com>
 */
final class ShowObjectController extends Controller
{
    public function __invoke(Request $request, string $key, ObjectKeyRepository $objectKeyRepository): JsonResponse
    {
        try {
            $timestamp = (int) $request->query('timestamp', null);

            $objectKey = $objectKeyRepository->findObjectByKey($key, $timestamp);

            return response()->json(
                [
                    'data' => new ObjectKeyResource($objectKey),
                ],
                200
            )->header('Content-Type', 'application/json');
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Object not found'], 404);
        }
    }
}
