<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as RoleSpatie;

class Role extends RoleSpatie
{
    use HasFactory;

    public $guard_name = 'sanctum';

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
                // ->orWhere('email', 'like', '%'.$search.'%');
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
