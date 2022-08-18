<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Familiar extends Model
{
    use HasFactory;

    protected $fillable = ['persona_id'];

    public function persona() {
        return $this->belongsTo('App\Models\Persona');
    }

    public function residente() {
        return $this->belongsToMany('App\Models\Residente');
    }


}
