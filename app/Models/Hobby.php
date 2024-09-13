<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hobby extends Model
{
    use HasFactory;

    protected $table = 'hobbies';

    protected $fillable = ['hobby_name'];

    public function users()
    {
        return $this->hasMany(User::class, 'hobbies_id');
    }

    public static function fetchAllHobbies()
    {
        return self::all();
    }
}
