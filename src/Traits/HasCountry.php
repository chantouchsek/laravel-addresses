<?php

namespace Chantouch\Addresses\Traits;

use Chantouch\Addresses\Models\Country;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class HasCountry
 * @package Chantouch\Addresses\Traits;
 * @property int|null $country_id
 * @property Country|null $country
 * @property string $country_code
 */
trait HasCountry
{
    /**
     * Get the models country.
     *
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the models country code.
     *
     * @return string
     */
    public function getCountryCodeAttribute(): string
    {
        if ($country = $this->country)
            return $country->iso_3166_2;

        return '';
    }

    /**
     * Scope by country.
     *
     * @param Builder $query
     * @param int $id
     * @return Builder
     */
    public function scopeByCountry(Builder $query, int $id): Builder
    {
        return $query->whereHas('country', function ($q) use ($id) {
            $q->where('id', $id);
        });
    }
}