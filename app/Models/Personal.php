<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    use HasFactory;

    protected $fillable = ['persona_id','ocupacion_id','matricula','ingreso','baja'];

    public function persona() {
        return $this->belongsTo('App\Models\Persona');
    }

    public function ocupacion() {
        return $this->belongsTo('App\Models\Ocupacion');
    }
}
