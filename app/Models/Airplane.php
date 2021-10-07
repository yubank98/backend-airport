<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airplane extends Model
{
    use HasFactory;
    protected $table = 'Airplane';
    protected $fillable = ['id','airline','model','desing','capacity'];
    public function airline(){
        return $this->belongsTo('App\Models\Airline','airline');
    }

    public function flights(){
        return $this->hasMany('App\Models\Flight','airplane');
    }

}
