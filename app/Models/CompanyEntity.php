<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class CompanyEntity extends Model
{
    protected $table = 'company_entities'; // OJO: singular
    public $timestamps = false;
}