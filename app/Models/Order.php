<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        "status", "total_price", "session_id", "user_id", "user_mail", "payement_id", "event_types", "date_event",
    ];
}
