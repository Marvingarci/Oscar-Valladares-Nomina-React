<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inability extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'type',
        'caption', 
        'start_date', 
        'end_date',
        'days_paid'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function type(){
        return $this->belongsTo(TypeInabilities::class);
    }


}
