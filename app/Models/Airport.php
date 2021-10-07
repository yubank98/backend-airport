<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    use HasFactory;
    protected $table = 'Airport';
    protected $fillable = ['id','name','city','country'];
    public function employee(){
        return $this->hasMany('App\Models\Employee','airport');
    }
    public function Output_Catalog(){
        return $this->hasMany('App\Models\FlightCatalog','departure');
    }
    public function Input_Catalog(){
        return $this->hasMany('App\Models\FlightCatalog','arrival');
    }

    public function Output_flights(){
        return $this->hasMany('App\Models\Flight','departure');
    }

    public function Input_flights(){
        return $this->hasMany('App\Models\Flight','arrival');
    }


}
