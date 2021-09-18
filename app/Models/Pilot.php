<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pilot extends Model
{
    use HasFactory;
    protected $table = 'Pilot';
    protected $fillable = ['id','idEmployee'];
    public function employee(){
        return $this->belongsTo('App\Models\Employee','idEmployee');
    }

    public function pilot_fly(){
        return $this->hasMany('App\Models\Flight','pilot');
    }

    public function Copilot_fly(){
        return $this->hasMany('App\Models\Flight','coPilot');
    }
}
