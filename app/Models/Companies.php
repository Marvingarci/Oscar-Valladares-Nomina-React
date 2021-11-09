<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Companies extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'phone'
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }


    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
                // ->orWhere('last_name', 'like', '%'.$search.'%')
                // ->orWhere('email', 'like', '%'.$search.'%')
                // ->orWhereHas('organization', function ($query) use ($search) {
                //     $query->where('name', 'like', '%'.$search.'%');
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
