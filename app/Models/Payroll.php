<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;


    protected $fillable = [
        'employee_id',
        'salary_structure_id',
        'start_date',
        'final_date',
        'ordinary_salary',
        'total_deduc',
        'total_inc',
        'total_to_pay',
        'days_worked',
        'status'
    ];


    public function salary_rules()
    {
        return $this->belongsToMany(SalaryRule::class, "payroll_detail")->as('detail')
            ->withTimestamps()
            ->withPivot('amount');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }


    public function salary_structure()
    {
        return $this->belongsTo(SalaryStructure::class);
    }


    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->orWhereHas('employee', function ($query) use ($search) {
                    $query->where('full_name', 'like', '%' . $search . '%');
                });
                //   ->orWhere('dep_code', 'like', '%' . $search . '%');
                // ->orWhere('email', 'like', '%'.$search.'%')
                // ->orWhereHas('position', function ($query) use ($search) {
                //     $query->where('name', 'like', '%' . $search . '%');
                // });
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
