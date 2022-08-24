<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Residente extends Model
{
    use HasFactory;

    protected $fillable = ['persona_id','ingreso','baja', 'habitacion_id'];

    public function persona() {
        return $this->belongsTo('App\Models\Persona');
    }

    public function habitacion() {
        return $this->belongsTo('App\Models\Habitacion');
    }

    public function familiars() {

        return $this->belongsToMany('App\Models\Familiar', 'residente_familiars')->withPivot('parentesco','principal');
    }

    public function medicos() {

        return $this->belongsToMany('App\Models\Medico', 'residente_medicos');
    }

    public function mutuals() {

        return $this->belongsToMany('App\Models\Mutual', 'residente_mutuals')->withPivot('credencial');;
    }

    public function medicamentos() {

        return $this->belongsToMany('App\Models\Medicamento', 'residente_medicamentos')->withPivot('toma','dosis','stock','alta');
    }
}
