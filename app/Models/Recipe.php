<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recipe extends Model
{
    /** @use HasFactory<\Database\Factories\RecipeFactory> */
    use HasFactory;

    protected $fillable = ['title', 'ingredients', 'description', 'avg_time', 'image_paths'];

    /**
     * Get the user that owns the Recipe
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getImageUrlsAttribute()
    {
        if (!$this->image_paths) {
            return [];
        }

        $imagePaths = json_decode($this->image_paths, true);

        return array_map(fn($path) => asset("storage/{$path}"), $imagePaths);
    }
}
