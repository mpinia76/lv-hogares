<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medico extends Model
{
    use HasFactory;

    protected $fillable = ['persona_id','especialidad_id','matricula'];

    public function persona() {
        return $this->belongsTo('App\Models\Persona');
    }

    public function especialidad() {
        return $this->belongsTo('App\Models\Especialidad');
    }
}
