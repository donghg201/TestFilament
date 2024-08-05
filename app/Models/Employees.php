<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'dob',
        'position',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function takeNameOfUserId($id)
    {
        switch ($id) {
            case 1:
                echo 'Admin';
                break;
            case 2:
                echo 'Guess';
                break;
            case 3:
                echo "Employee";
                break;
        }
    }
}
