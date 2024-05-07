<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evenement extends Model
{
    use HasFactory;


    public function clients()
    {
    }
    protected $fillable = [
        "types",
        "date_evenement",
        "date_fin",
        "prix",
        "validation",
        "note_evenement",
        "client_id",
        "stripe_event_id",
        "lookup_key"
    ];
}
