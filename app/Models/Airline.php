<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airline extends Model
{
    use HasFactory;
    protected $table = 'Airline';
    protected $fillable = ['id','name','alias'];
    public function airplanes(){
        return $this->hasMany('App\Models\Airplane','airline');
    }
    public function Flight_Catalogs(){
        return $this->hasMany('App\Models\FlightCatalog','airline');
    }
}
