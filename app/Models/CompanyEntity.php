<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class CompanyEntity extends Model
{
    use HasFactory;

    protected $table = 'company_entities'; // OJO: singular
    public $timestamps = false;
    protected $fillable = ['name']; // agrega otras si las usás (e.g., 'slug', 'description')

}