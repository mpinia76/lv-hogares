<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Residente extends Model
{
    use HasFactory;

    protected $fillable = ['persona_id','ingreso','baja'];

    public function persona() {
        return $this->belongsTo('App\Persona');
    }
}
