<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherDeduction extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'name', 'monto', 'cuota','pend',
        'status'
    ];



    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
