<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'Employee';
    protected $fillable = ['id','name','surname','airport'];
    public function airport(){
        return $this->belongsTo('App\Models\Airport','airport');
    }
    public function user(){
        return $this->belongsTo('App\Models\User','idEmployee');
    }
}
