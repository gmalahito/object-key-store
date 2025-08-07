<?php

use App\DTOs\ObjectKeyDTO;
use App\DTOs\ObjectKeyStoreDTO;
use App\Repositories\ObjectKeyRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\ModelNotFoundException;

uses(Tests\TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->repository = new ObjectKeyRepository();
});

it('can get all objects in ascending order', function () {
    $this->repository->store(ObjectKeyStoreDTO::fromRequest(['key1' => 'string-data-1']));
    $this->repository->store(ObjectKeyStoreDTO::fromRequest(['key2' => 'string-data-2']));
    $this->repository->store(ObjectKeyStoreDTO::fromRequest(['key3' => 'string-data-3']));

    $objects = $this->repository->get();

    expect($objects)->toHaveCount(3);
    expect($objects->first()->key)->toBe('key1');
});

it('can get all objects in descending order', function () {
    $this->repository->store(ObjectKeyStoreDTO::fromRequest(['key1' => 'string-data-1']));
    $this->repository->store(ObjectKeyStoreDTO::fromRequest(['key2' => 'string-data-2']));

    $objects = $this->repository->get('desc');

    expect($objects)->toHaveCount(2);
    expect($objects->first()->key)->toBe('key2');
});

it('can add an object with string value', function () {
    $data = [
        'test-key-string' => 'some string data',
    ];

    $objectKey = $this->repository->store(ObjectKeyStoreDTO::fromRequest($data));

    expect($objectKey)->not->toBeNull()
        ->and($objectKey->key)->toBe('test-key-string')
        ->and($objectKey->value)->toBe('some string data');
});

it('can add an object with blob value', function () {
    $data = [
        'test-key-blob' => base64_encode('binary blob data'),
    ];

    $objectKey = $this->repository->store(ObjectKeyStoreDTO::fromRequest($data));

    expect($objectKey)->not->toBeNull()
        ->and($objectKey->key)->toBe('test-key-blob')
        ->and($objectKey->value)->toBe(base64_encode('binary blob data'));
});

it('throws exception when key is missing', function () {
    $data = [null => 'some value'];

    expect(fn () => $this->repository->store(ObjectKeyStoreDTO::fromRequest($data)))
        ->toThrow(\InvalidArgumentException::class, 'Object key is required.');
});

it('throws exception when key is empty', function () {
    $data = ['' => 'some value'];

    expect(fn () => $this->repository->store(ObjectKeyStoreDTO::fromRequest($data)))
        ->toThrow(\InvalidArgumentException::class, 'Object key is required.');
});

it('throws exception when value is missing', function () {
    $data = ['key' => null];

    expect(fn () => $this->repository->store(ObjectKeyStoreDTO::fromRequest($data)))
        ->toThrow(\InvalidArgumentException::class, 'Object value is required.');
});

it('throws exception when value is empty', function () {
    $data = ['test-key' => ''];

    expect(fn () => $this->repository->store(ObjectKeyStoreDTO::fromRequest($data)))
        ->toThrow(\InvalidArgumentException::class, 'Object value is required.');
});

it('can find object by key without timestamp', function () {
    $objectKey = $this->repository->store(ObjectKeyStoreDTO::fromRequest(['123' => 'test-value']));

    $found = $this->repository->findByKey('123', null);

    expect($found)->not->toBeNull()
        ->and($found->key)->toBe('123')
        ->and($found->value)->toBe('test-value');
});

it('can find object by key with timestamp filter', function () {
    $objectKey = $this->repository->store(ObjectKeyStoreDTO::fromRequest(['456' => 'test-value-2']));

    $futureTimestamp = now()->addHour()->timestamp;
    $found = $this->repository->findByKey('456', $futureTimestamp);

    expect($found)->not->toBeNull()
        ->and($found->key)->toBe('456');
});

it('returns null when object not found by key', function () {
    expect(fn () => $this->repository->findByKey('999', null))
        ->toThrow(ModelNotFoundException::class);
});

it('returns null when object created after timestamp', function () {
    $this->repository->store(ObjectKeyStoreDTO::fromRequest(['789' => 'test-value-3']));

    $pastTimestamp = now()->subHour()->timestamp;

    expect(fn () => $this->repository->findByKey('789', $pastTimestamp))
        ->toThrow(ModelNotFoundException::class);
});

it('returns null when object key not found', function () {
    expect(fn () => $this->repository->findByKey('non-existent-key'))
        ->toThrow(ModelNotFoundException::class);
});
