<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * ObjectKey class
 *
 * @since Aug 01, 2025
 *
 * @author Greg Malahito <mgmalahito@gmail.com>
 */
final class ObjectKey extends Model
{
    /** @use HasFactory<\Database\Factories\ObjectKeyFactory> */
    use HasFactory;

    protected $table = 'object_keys';

    protected $fillable = ['key', 'value', 'type'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope to filter by timestamp
     */
    public function scopeCreatedBefore($query, int $timestamp)
    {
        $date = Carbon::createFromTimestamp($timestamp);

        return $query->where('created_at', '<=', $date);
    }

    /**
     * Get the latest record for a specific key
     */
    public function scopeLatestByKey($query, string $key)
    {
        return $query->where('key', $key)->latest('created_at');
    }
}
