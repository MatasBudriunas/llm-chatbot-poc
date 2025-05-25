<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $slug
 * @property string $name
 * @property string $personality
 */
class Agent extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'persona',
    ];
}
