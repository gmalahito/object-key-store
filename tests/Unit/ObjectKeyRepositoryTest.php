<?php

use App\Repositories\ObjectKeyRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
uses(Tests\TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->repository = new ObjectKeyRepository();
});

it('can get all objects in ascending order', function () {
    $this->repository->addObject(['key' => 'key1', 'value' => 'blob-data-1']);
    $this->repository->addObject(['key' => 'key2', 'value' => 'blob-data-2']);
    $this->repository->addObject(['key' => 'key3', 'value' => 'blob-data-3']);

    $objects = $this->repository->getAllObjects();

    expect($objects)->toHaveCount(3);
    expect($objects->first()->key)->toBe('key1');
});

it('can get all objects in descending order', function () {
    $this->repository->addObject(['key' => 'key1', 'value' => 'blob-data-1']);
    $this->repository->addObject(['key' => 'key2', 'value' => 'blob-data-2']);

    $objects = $this->repository->getAllObjects('desc');

    expect($objects)->toHaveCount(2);
    expect($objects->first()->key)->toBe('key2');
});

it('can add an object with string value', function () {
    $data = [
        'key' => 'test-key-string',
        'value' => 'some string data',
    ];

    $objectKey = $this->repository->addObject($data);

    expect($objectKey)->not->toBeNull()
        ->and($objectKey->key)->toBe('test-key-string')
        ->and($objectKey->value)->toBe('some string data');
});

it('can add an object with blob value', function () {
    $data = [
        'key' => 'test-key-blob',
        'value' => base64_encode('binary blob data'),
    ];

    $objectKey = $this->repository->addObject($data);

    expect($objectKey)->not->toBeNull()
        ->and($objectKey->key)->toBe('test-key-blob')
        ->and($objectKey->value)->toBe(base64_encode('binary blob data'));
});

it('throws exception when key is missing', function () {
    $data = ['value' => 'some value'];

    expect(fn() => $this->repository->addObject($data))
        ->toThrow(\InvalidArgumentException::class, 'Object key is required.');
});

it('throws exception when key is empty', function () {
    $data = ['key' => '', 'value' => 'some value'];

    expect(fn() => $this->repository->addObject($data))
        ->toThrow(\InvalidArgumentException::class, 'Object key is required.');
});

it('throws exception when value is missing', function () {
    $data = ['key' => 'test-key'];

    expect(fn() => $this->repository->addObject($data))
        ->toThrow(\InvalidArgumentException::class, 'Object value is required.');
});

it('throws exception when value is empty', function () {
    $data = ['key' => 'test-key', 'value' => ''];

    expect(fn() => $this->repository->addObject($data))
        ->toThrow(\InvalidArgumentException::class, 'Object value is required.');
});

it('can find object by key without timestamp', function () {
    $objectKey = $this->repository->addObject(['key' => '123', 'value' => 'test-value']);

    $found = $this->repository->findObjectByKey('123', null);

    expect($found)->not->toBeNull()
        ->and($found->key)->toBe('123')
        ->and($found->value)->toBe('test-value');
});

it('can find object by key with timestamp filter', function () {
    $objectKey = $this->repository->addObject(['key' => '456', 'value' => 'test-value-2']);

    $futureTimestamp = now()->addHour()->timestamp;
    $found = $this->repository->findObjectByKey('456', $futureTimestamp);

    expect($found)->not->toBeNull()
        ->and($found->key)->toBe('456');
});

it('returns null when object not found by key', function () {
    $found = $this->repository->findObjectByKey('999', null);

    expect($found)->toBeNull();
});

it('returns null when object created after timestamp', function () {
    $this->repository->addObject(['key' => '789', 'value' => 'test-value-3']);

    $pastTimestamp = now()->subHour()->timestamp;
    $found = $this->repository->findObjectByKey('789', $pastTimestamp);

    expect($found)->toBeNull();
});

it('returns null when object key not found', function () {
    $result = $this->repository->findObjectByKey('non-existent-key');

    expect($result)->toBeNull();
});
