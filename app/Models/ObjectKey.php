<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    protected $fillable = ['key', 'value'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
