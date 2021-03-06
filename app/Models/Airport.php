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
}
