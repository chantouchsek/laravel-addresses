<?php

namespace Chantouch\Addresses\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Country
 * @package Chantouch\Addresses\Models
 * @property string|null $capital
 * @property string|null $citizenship
 * @property string|null $country_code
 * @property string|null $currency
 * @property string|null $currency_code
 * @property string|null $currency_sub_unit
 * @property string|null $currency_symbol
 * @property int|null $currency_decimals
 * @property string|null $full_name
 * @property string|null $iso_3166_2
 * @property string|null $iso_3166_3
 * @property string|null $name
 * @property string|null $region_code
 * @property string|null $sub_region_code
 * @property boolean|null $eea
 * @property string|null $calling_code
 * @property string|null $flag
 */
class Country extends Model {
    
    /**
     * @var array
     */
    protected array $countries = [];
    
    /**
     * @var string
     * The table for the countries in the database, is "countries" by default.
     */
    protected $table;

    /** @inheritdoc */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('laravel-address.countries.table', 'countries');
    }
    
    /**
     * Get the countries from the JSON file, if it hasn't already been loaded.
     *
     * @return array
     */
    protected function getCountries(): array
    {
        //Get the countries from the JSON file
        if (is_null($this->countries) || empty($this->countries)) {
            $this->countries = json_decode(file_get_contents(__DIR__ . '/database/countries.json'), true);
        }
        
        //Return the countries
        return $this->countries;
    }
    
    /**
     * Returns one country
     *
     * @param string $id The country id
     *
     * @return array
     */
    public function getOne(string $id): array
    {
        $countries = $this->getCountries();
        return $countries[$id];
    }
    
    /**
     * Returns a list of countries
     *
     * @param string sort
     *
     * @return array
     */
    public function getList($sort = null): array
    {
        //Get the countries list
        $countries = $this->getCountries();
        
        //Sorting
        $validSorts = [
            'capital',
            'citizenship',
            'country-code',
            'currency',
            'currency_code',
            'currency_sub_unit',
            'full_name',
            'iso_3166_2',
            'iso_3166_3',
            'name',
            'region-code',
            'sub-region-code',
            'eea',
            'calling_code',
            'currency_symbol',
            'flag',
        ];
        
        if (!is_null($sort) && in_array($sort, $validSorts)){
            uasort($countries, function($a, $b) use ($sort) {
                if (!isset($a[$sort]) && !isset($b[$sort])){
                    return 0;
                } elseif (!isset($a[$sort])){
                    return -1;
                } elseif (!isset($b[$sort])){
                    return 1;
                } else {
                    return strcasecmp($a[$sort], $b[$sort]);
                }
            });
        }
        
        //Return the countries
        return $countries;
    }
    
    /**
     * Returns a list of countries suitable to use with a select element in Laravelcollective\html
     * Will show the value and sort by the column specified in the display attribute
     *
     * @param string display
     *
     * @return array
     */
    public function getListForSelect($display = 'name'): array
    {
        $countries = [];
        foreach ($this->getList($display) as $key => $value) {
            $countries[$key] = $value[$display];
        }
        
        //return the array
        return $countries;
    }
}
