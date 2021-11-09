<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'department_id',
        'position_id', 'full_name', 'gender',
        'company_id', 'date_of_birth', 'identy', 
        'address', 'phone_number', 'employee_code'
    ];


    public function department()
    {
        return $this->belongsTo(Department::class);
    }


    public function position()
    {
        return $this->belongsTo(Position::class);
    }


    public function tickets()
    {
        return $this->belongsToMany(Employee::class);
    }


    public function loans()
    {
        return $this->hasMany(Loan::class);
    }


    public function glasses()
    {
        return $this->hasMany(Glasses::class);
    }


    public function provisions()
    {
        return $this->hasMany(Provision::class);
    }


    public function otherDeductions()
    {
        return $this->hasMany(OtherDeduction::class);
    }


    public function inabilities()
    {
        return $this->hasMany(Inability::class);
    }


    public function company()
    {
        return $this->belongsTo(Companies::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('full_name', 'like', '%' . $search . '%')
                    //   ->orWhere('dep_code', 'like', '%' . $search . '%');
                    // ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhereHas('position', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
            });
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }
}
