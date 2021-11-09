<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenerateTickets extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
