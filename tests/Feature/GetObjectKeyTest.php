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
