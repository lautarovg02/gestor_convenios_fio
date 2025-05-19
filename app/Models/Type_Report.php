<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Type_Report extends Model
{
    use HasFactory;
    protected $fillable = ['type'];

    //Relación 1:n atributo multivaluado en la tabla TypeReport
      public function reportTypes(): HasMany
      {
          return $this->hasMany(report_specific::class, 'type_report_id');
      }
}
