<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class admin extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'prenom', 'mdp'];

    // Mutateur pour le champ 'nom' : convertit le nom en majuscules
    public function setNomAttribute($value)
    {
        $this->attributes['nom'] = mb_strtoupper($value);
    }
}
