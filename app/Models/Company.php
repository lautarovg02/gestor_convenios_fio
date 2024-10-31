<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Company
 *
 * @property $id
 * @property $denomination
 * @property $cuit
 * @property $company_name
 * @property $sector
 * @property $entity
 * @property $company_category
 * @property $scope
 * @property $street
 * @property $number
 * @property $city_id
 * @property $created_at
 * @property $updated_at
 *
 * @property City $city
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Company extends Model
{
    use HasFactory;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['denomination','cuit','company_name','sector','entity','company_category','scope','street','number','city_id'];

    /**
     * Relación 1:*
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function city():HasOne
    {
        return $this->hasOne(City::class , 'id', 'city_id');
    }

}
