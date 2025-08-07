<?php

use App\Models\ObjectKey;
use Illuminate\Support\Arr;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create an object successfully', function () {
    $data = [ 'test-key-123' => 'some test data'];

    $response = $this->postJson('/api/v1/object-keys', $data);

    $response->assertStatus(201)
        ->assertJson(['message' => 'Object created successfully']);

    $key = array_key_first($data);

    $this->assertDatabaseHas('object_keys', [
        'key'   => $key,
        'value' => Arr::get($data, $key)
    ]);
});

it('can create an object with blob data', function () {
    $blobData = base64_encode('binary blob data');
    $data     = [
        'blob-key-456' => $blobData
    ];

    $response = $this->postJson('/api/v1/object-keys', $data);

    $response->assertStatus(201)
        ->assertJson(['message' => 'Object created successfully']);

    $this->assertDatabaseHas('object_keys', [
        'key'   => 'blob-key-456',
        'value' => $blobData
    ]);
});

it('returns validation error when key is null or missing', function () {
    $data = [null => 'some value'];

    $response = $this->postJson('/api/v1/object-keys', $data);

    $response->assertStatus(422)
       ->assertJsonStructure(['message', 'errors']);
});

it('returns validation error when key is empty', function () {
    $data = ['' => 'some value'];

    $response = $this->postJson('/api/v1/object-keys', $data);

    $response->assertStatus(422)
       ->assertJsonStructure(['message', 'errors']);
});

it('returns validation error when value is null or missing', function () {
    $data = ['key' => null];

    $response = $this->postJson('/api/v1/object-keys', $data);

    $response->assertStatus(422)
        ->assertJsonStructure(['message', 'errors']);
});

it('returns validation error when value is empty', function () {
    $data = ['test-key' => ''];

    $response = $this->postJson('/api/v1/object-keys', $data);

    $response->assertStatus(422)
        ->assertJsonStructure(['message', 'errors']);
});

it('handles database errors gracefully', function () {
    // Mock the repository to throw an exception
    $this->mock(\App\Repositories\ObjectKeyRepository::class)
        ->shouldReceive('store')
        ->andThrow(new \Exception('Database connection failed'));

    $data = ['test-key' => 'test-value'];

    $response = $this->postJson('/api/v1/object-keys', $data);

    $response->assertStatus(400)
        ->assertJson(['error' => 'An error occurred while creating the object']);
});

it('returns correct content type header', function () {
    $data = ['content-test' => 'test data'];

    $response = $this->postJson('/api/v1/object-keys', $data);

    $response->assertHeader('content-type', 'application/json');
});
