<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicamento extends Model
{
    use HasFactory;

    protected $fillable = ['nombre','generico'];

    public function getFullNameAttribute()
    {
        return $this->nombre . '/' . $this->generico;
    }

    public function getStockActualAttribute()
    {

        $hoy =(empty($this->pivot->suspension))?now():(($this->pivot->suspension>now())?now():$this->pivot->suspension);
        $dias = Carbon::parse($this->pivot->alta)->diffInDays(Carbon::parse($hoy));
        $actual = $this->pivot->stock - ($this->pivot->dosis*$dias);
        return $actual;

    }

    public function getReposicionAttribute()
    {
        $dias = $this->pivot->stock / $this->pivot->dosis;
        $reposicion = Carbon::parse($this->pivot->alta)->addDays($dias);
        return $reposicion;

    }

    public function getReponerAttribute()
    {
        $dias = Carbon::parse(now())->diffInDays($this->getReposicionAttribute());

        return ($dias>=10);

    }
}
