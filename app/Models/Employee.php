<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable
{
    use HasFactory;

    // Disable timestamps for this model
    public $timestamps = true;

    // Table and primary key settings
    protected $table = 'employees';
    protected $primaryKey = 'employee_id';


    // Fillable fields
    protected $fillable = [
        'firstname',
        'lastname',
        'middle',
        'suffix',
        'age',
        'address',
        'contact_number',
        'gender',
        'role',
        'username',
        'password',
        'status',
        'avatar',
    ];

    // Hidden fields
    protected $hidden = [
        'password',
    ];

    // Data type casts
    protected $casts = [
        'age' => 'integer',
    ];

    // Search scope
    public function scopeSearch($query, $value)
    {
        return $query->where('firstname', 'like', '%'.$value.'%')
                     ->orWhere('lastname', 'like', '%'.$value.'%')
                     ->orWhere('middle', 'like', '%'.$value.'%')
                     ->orWhere('suffix', 'like', '%'.$value.'%')
                     ->orWhere('address', 'like', '%'.$value.'%')
                     ->orWhere('contact_number', 'like', '%'.$value.'%');
    }



}
