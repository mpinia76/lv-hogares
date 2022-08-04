<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'apellido','email','telefono','domicilio','observaciones','tipoDocumento','documento','nacimiento','cuil','genero','foto','fallecimiento'];

    public function residente()
    {
        return $this->hasOne('App\Residente');
    }

    public function getFullNameAttribute()
    {
        return $this->apellido . ', ' . $this->nombre;
    }

    public function getFullNameAgeAttribute()
    {
        return $this->apellido . ', ' . $this->nombre.' ('.$this->getAgeAttribute().')';
    }

    public function getAgeAttribute()
    {
        if (!is_null($this->fallecimiento))
        {
            return Carbon::parse($this->nacimiento)->diff(Carbon::parse($this->fallecimiento))->format('%y').' años ('.date('d/m/Y', strtotime($this->nacimiento)).'-'.date('d/m/Y', strtotime($this->fallecimiento)).')';
        }
        if (!is_null($this->nacimiento))
        {
            return Carbon::parse($this->nacimiento)->age.' años';
        }

    }
}
