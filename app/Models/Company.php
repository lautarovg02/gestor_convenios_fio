<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Str;

/**
 * Class Company
 *
 * @property $id
 * @property $denomination
 * @property $cuit
 * @property $company_name
 * @property $sector
 * @property $entity_id
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
    protected $fillable = ['denomination','cuit','company_name','sector','entity_id','company_category','scope','street','number','city_id', 'slug'];

    /**
     * Relación 1:*
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function city():HasOne
    {
        return $this->hasOne(City::class , 'id', 'city_id');
    }

    /**
     * Relación: una compañía tiene una entidad.
     */
    public function entity()
    {
        return $this->belongsTo(CompanyEntity::class);
    }

    // Scope para la búsqueda
    public function scopeSearch($query, $searchTerm)
    {
        if ($searchTerm) {

            $terms = explode(' ', $searchTerm);

            foreach ($terms as $term) {
                $query->where(function ($subQuery) use ($term) {
                    $subQuery->where('denomination', 'LIKE', "%{$term}%")
                         ->orWhere('company_name', 'LIKE', "%{$term}%")
                         ->orWhere('cuit', 'LIKE', "%{$term}%")
                         ->orWhere('sector', 'LIKE', "%{$term}%")
                        //  ->orWhere('entity_id', 'LIKE', "%{$term}%")
                         ->orWhereHas('entity', function ($query) use ($term) {
                            $query->where('name', 'LIKE', "%{$term}%");
                        })
                         ->orWhere('company_category', 'LIKE', "%{$term}%")
                         ->orWhereHas('city', function ($query) use ($term) {
                             $query->where('name', 'LIKE', "%{$term}%");
                         });
                });
            }
        }
        return $query;
    }

    //Scope que devuelve a las empresas que están activas
    public function scopeEnabled($query) {
        return $query->where('is_enabled', true);
    }

      // Mutator para generar el slug automáticamente basado en la denominación
    public function setDenominationAttribute($value)
    {
        $this->attributes['denomination'] = $value;
        $this->attributes['slug'] = Str::slug($value) . '-' . uniqid();
    }

    //Scope para filtrar
    public function scopefilter($query , array $filters )
    {
            $query->when($filters['city']?? null, fn($q, $city) => $q->where('city_id',$city))
                    ->when($filters['sector'] ?? null, function($q, $sector){
                if ($sector === 'N/A') {
                    $q->whereNull('sector');
                } else {
                    $q->where('sector', $sector);
                }
            })
            ->when($filters['scope'] ?? null, function($q, $scope) {
                if($scope ===' N/A') {
                    $q->whereNull('scope');
                }else{
                    $q->where('scope', $scope);
                }
            });

            return $query;

    }

}
