<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('object_keys', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->comment('Unique identifier for the object');
            $table->text('value')->comment('Value associated with the object key');
            $table->timestamp('created_at')->useCurrent()->comment('Timestamp when the object was created');
            $table->timestamp('updated_at')->useCurrent()->nullable()->comment('Timestamp when the object was last updated');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('object_keys');
    }
};
