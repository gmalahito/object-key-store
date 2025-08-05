<?php

declare(strict_types=1);

use App\Models\ObjectKey;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can retrieve all object keys', function () {
    $objectKeyData1 = ['key1' => 'some test data'];
    $objectKeyData2 = ['key2' => 'some other test data'];

    $this->postJson('/api/v1/object-keys', $objectKeyData1)->assertStatus(201);
    $this->postJson('/api/v1/object-keys', $objectKeyData2)->assertStatus(201);

    $response = $this->getJson('/api/v1/object-keys');

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data')
        ->assertJsonFragment($objectKeyData1)
        ->assertJsonFragment($objectKeyData2);

    $response = $this->getJson('/api/v1/object-keys?order=desc');

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data')
        ->assertJsonFragment($objectKeyData2)
        ->assertJsonFragment($objectKeyData1);
});

it('can retrieve a specific object key by its key', function () {
    $objectKeyData = ['test-key-123' => 'some test data'];

    $this->postJson('/api/v1/object-keys', $objectKeyData)->assertStatus(201);

    $key = array_key_first($objectKeyData);
    $response = $this->getJson("/api/v1/object-keys/{$key}");

    $response->assertStatus(200)
        ->assertJsonFragment($objectKeyData);
});

it('returns 404 when object key not found', function () {
    $response = $this->getJson('/api/v1/object-keys/non-existent-key');

    $response->assertStatus(404)
        ->assertJson(['message' => 'Object not found']);
});


it('can retrieve a specific object key by its key with timestamp', function () {
    $key            = 'test-key-456';
    $objectKeyData1 = [$key => 'some test data'];

    $this->postJson('/api/v1/object-keys', $objectKeyData1)->assertStatus(201);

    sleep(1); // Ensure a different timestamp

    // Add another object with the same key but different value
    $objectKeyData2 = [$key => 'some test other data'];

    $this->postJson('/api/v1/object-keys', $objectKeyData2)->assertStatus(201);

    $timestamp = now()->subSeconds(1)->timestamp;

    $response = $this->getJson("/api/v1/object-keys/{$key}?timestamp={$timestamp}");

    $response->assertStatus(200)
        ->assertJsonFragment($objectKeyData1);

    $timestamp = now()->addSeconds(2)->timestamp;

    $response = $this->getJson("/api/v1/object-keys/{$key}?timestamp={$timestamp}");

    $response->assertStatus(200)
        ->assertJsonFragment($objectKeyData2);
});
