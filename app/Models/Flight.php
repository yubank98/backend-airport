<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    use HasFactory;
    protected $table = 'Flight';
    protected $fillable = ['id','departure','arrival','assignedDate','passengers','pilot','coPilot','airplane'];
    public function departure(){
        return $this->belongsTo('App\Models\Airport','departure');
    }
    public function arrival(){
        return $this->belongsTo('App\Models\Airport','arrival');
    }

    public function pilot(){
        return $this->belongsTo('App\Models\Pilot','pilot');
    }

    public function coPilot(){
        return $this->belongsTo('App\Models\Pilot','coPilot');
    }

    public function airplane(){
        return $this->belongsTo('App\Models\Airplane','airplane');
    }

    
}
