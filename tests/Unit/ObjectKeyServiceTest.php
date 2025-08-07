<?php

declare(strict_types=1);

use App\Models\ObjectKey;
use App\DTOs\ObjectKeyStoreDTO;
use App\DTOs\ObjectKeyFilterDTO;
use App\Services\ObjectKeyService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\Contracts\ObjectKeyRepositoryInterface;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->repository = Mockery::mock(ObjectKeyRepositoryInterface::class);
    $this->service = new ObjectKeyService($this->repository);
});

afterEach(function () {
    Mockery::close();
});



describe('getAllObjectKeys', function () {
    it('returns all object keys with default order', function () {
        // Arrange
        $dto = new ObjectKeyFilterDTO(order: 'asc');
        $expectedCollection = new Collection([
            new ObjectKey(['key' => 'test1', 'value' => 'value1']),
            new ObjectKey(['key' => 'test2', 'value' => 'value2']),
        ]);

        $this->repository
            ->shouldReceive('get')
            ->once()
            ->with('asc')
            ->andReturn($expectedCollection);

        // Act
        $result = $this->service->getAllObjectKeys($dto);

        // Assert
        expect($result)->toBe($expectedCollection);
    });

    it('returns all object keys with desc order', function () {
        // Arrange
        $dto = new ObjectKeyFilterDTO(order: 'desc');
        $expectedCollection = new Collection([
            new ObjectKey(['key' => 'test2', 'value' => 'value2']),
            new ObjectKey(['key' => 'test1', 'value' => 'value1']),
        ]);

        $this->repository
            ->shouldReceive('get')
            ->once()
            ->with('desc')
            ->andReturn($expectedCollection);

        // Act
        $result = $this->service->getAllObjectKeys($dto);

        // Assert
        expect($result)->toBe($expectedCollection);
    });
});

describe('getObjectByKey', function () {
    it('throws exception when key is not provided', function () {
        // Arrange
        $dto = new ObjectKeyFilterDTO();

        // Act & Assert
        expect(fn () => $this->service->getObjectByKey($dto))
            ->toThrow(InvalidArgumentException::class, 'Key is required');
    });
});

describe('createObjectKey', function () {
    it('creates object key successfully with valid DTO', function () {
        // Arrange
        $dto = new ObjectKeyStoreDTO('test-key', 'test-value', 'string');
        $expectedObjectKey = new ObjectKey([
            'key' => 'test-key',
            'value' => 'test-value',
            'type' => 'string'
        ]);

        $this->repository
            ->shouldReceive('store')
            ->once()
            ->with($dto)
            ->andReturn($expectedObjectKey);

        // Act
        $result = $this->service->createObjectKey($dto);

        // Assert
        expect($result)->toBe($expectedObjectKey);
    });

    it('creates object key with blob type', function () {
        // Arrange
        $base64Value = base64_encode('binary data content');
        $dto = new ObjectKeyStoreDTO('binary-key', $base64Value, 'blob');
        $expectedObjectKey = new ObjectKey([
            'key' => 'binary-key',
            'value' => $base64Value,
            'type' => 'blob'
        ]);

        $this->repository
            ->shouldReceive('store')
            ->once()
            ->with($dto)
            ->andReturn($expectedObjectKey);

        // Act
        $result = $this->service->createObjectKey($dto);

        // Assert
        expect($result)->toBe($expectedObjectKey);
    });

    it('throws exception when DTO validation fails', function () {
        // Arrange
        $dto = Mockery::mock(ObjectKeyStoreDTO::class);
        $dto->shouldReceive('validate')
            ->once()
            ->andThrow(new InvalidArgumentException('Invalid DTO'));

        // Act & Assert
        expect(fn () => $this->service->createObjectKey($dto))
            ->toThrow(InvalidArgumentException::class, 'Invalid DTO');
    });
});
