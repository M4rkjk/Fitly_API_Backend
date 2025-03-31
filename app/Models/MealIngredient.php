<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MealIngredient extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get the meal that owns the MealIngredient
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function meal(): BelongsTo
    {
        return $this->belongsTo(Meal::class);
    }

    /**
     * Get the ingredients that owns the MealIngredient
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ingredients(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }

    /**
     * Get the user that owns the MealIngredient
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
