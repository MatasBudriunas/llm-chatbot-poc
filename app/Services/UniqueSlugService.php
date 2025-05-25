<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

class UniqueSlugService
{
    public function generateUniqueSlug(Model $model, string $base): string
    {
        $slug = $base;
        $counter = 1;

        while ($this->slugExists($model, $slug)) {
            $slug = "$base-$counter";
            $counter++;
        }

        return $slug;
    }

    private function slugExists(Model $model, string $slug): bool
    {
        return $model->newQuery()->where('slug', $slug)->exists();
    }
}
