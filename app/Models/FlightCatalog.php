<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightCatalog extends Model
{
    use HasFactory;
    protected $table = 'Flight_Catalog';
    protected $fillable =['id','aeroline','departure','arrival','estimated_time',
    'price_ticket','status'];
    public function aeroline(){
        return $this->belongsTo('App\Models\Airline','aeroline');
    }
    public function departure(){
        return $this->belongsTo('App\Models\Airport','departure');
    }
    public function arrival(){
        return $this->belongsTo('App\Models\Airport','arrival');
    }
}
