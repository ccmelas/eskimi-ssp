<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes
     * @var string[]
     */
    protected $fillable = ['name', 'from', 'to', 'daily_budget'];

    /**
     * Append this attributes to the json payload
     * @var string[]
     */
    protected $appends = ['total_budget'];

    /**
     * Cast attributes to particular formats
     * @var string[]
     */
    protected $casts = [
        'from' => 'date',
        'to' => 'date',
    ];

    /**
     * Returns attached images
     * @return HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    /**
     * Calculates and returns the total budget for a campaign
     * @return float|int
     */
    protected function getTotalBudgetAttribute()
    {
        return $this->getAttribute('daily_budget')
            * $this->getAttribute('from')->diffInDays($this->getAttribute('to'));
    }
}
