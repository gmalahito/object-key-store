<?php

use App\Repositories\ObjectKeyRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->repository = new ObjectKeyRepository();
});

it('can get all objects in ascending order', function () {
    $this->repository->addObject(['key1' => 'blob-data-1']);
    $this->repository->addObject(['key2' => 'blob-data-2']);
    $this->repository->addObject(['key3' => 'blob-data-3']);

    $objects = $this->repository->getAllObjects();

    expect($objects)->toHaveCount(3);
    expect($objects->first()->key)->toBe('key1');
});

it('can get all objects in descending order', function () {
    $this->repository->addObject(['key1' => 'blob-data-1']);
    $this->repository->addObject(['key2' => 'blob-data-2']);

    $objects = $this->repository->getAllObjects('desc');

    expect($objects)->toHaveCount(2);
    expect($objects->first()->key)->toBe('key2');
});

it('can add an object with string value', function () {
    $data = [
        'test-key-string' => 'some string data',
    ];

    $objectKey = $this->repository->addObject($data);

    expect($objectKey)->not->toBeNull()
        ->and($objectKey->key)->toBe('test-key-string')
        ->and($objectKey->value)->toBe('some string data');
});

it('can add an object with blob value', function () {
    $data = [
        'test-key-blob' => base64_encode('binary blob data'),
    ];

    $objectKey = $this->repository->addObject($data);

    expect($objectKey)->not->toBeNull()
        ->and($objectKey->key)->toBe('test-key-blob')
        ->and($objectKey->value)->toBe(base64_encode('binary blob data'));
});

it('throws exception when key is missing', function () {
    $data = [null => 'some value'];

    expect(fn () => $this->repository->addObject($data))
        ->toThrow(\InvalidArgumentException::class, 'Object key is required.');
});

it('throws exception when key is empty', function () {
    $data = ['' => 'some value'];

    expect(fn () => $this->repository->addObject($data))
        ->toThrow(\InvalidArgumentException::class, 'Object key is required.');
});

it('throws exception when value is missing', function () {
    $data = ['key' => null];

    expect(fn () => $this->repository->addObject($data))
        ->toThrow(\InvalidArgumentException::class, 'Object value is required.');
});

it('throws exception when value is empty', function () {
    $data = ['test-key' => ''];

    expect(fn () => $this->repository->addObject($data))
        ->toThrow(\InvalidArgumentException::class, 'Object value is required.');
});

it('can find object by key without timestamp', function () {
    $objectKey = $this->repository->addObject(['123' => 'test-value']);

    $found = $this->repository->findObjectByKey('123', null);

    expect($found)->not->toBeNull()
        ->and($found->key)->toBe('123')
        ->and($found->value)->toBe('test-value');
});

it('can find object by key with timestamp filter', function () {
    $objectKey = $this->repository->addObject(['456' => 'test-value-2']);

    $futureTimestamp = now()->addHour()->timestamp;
    $found = $this->repository->findObjectByKey('456', $futureTimestamp);

    expect($found)->not->toBeNull()
        ->and($found->key)->toBe('456');
});

it('returns null when object not found by key', function () {
    expect(fn () => $this->repository->findObjectByKey('999', null))
        ->toThrow(ModelNotFoundException::class);
});

it('returns null when object created after timestamp', function () {
    $this->repository->addObject(['789' => 'test-value-3']);

    $pastTimestamp = now()->subHour()->timestamp;

    expect(fn () => $this->repository->findObjectByKey('789', $pastTimestamp))
        ->toThrow(ModelNotFoundException::class);
});

it('returns null when object key not found', function () {
    expect(fn () => $this->repository->findObjectByKey('non-existent-key'))
        ->toThrow(ModelNotFoundException::class);
});
