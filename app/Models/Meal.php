<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Meal extends Model
{
    /** @use HasFactory<\Database\Factories\MealFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $guarded = ['id'];

    /**
     * The user that belong to the Meal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('amount');
    }

}


