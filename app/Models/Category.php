<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = ['category_name'];

    public function users()
    {
        return $this->hasMany(User::class, 'category_id');
    }

    public static function fetchAllCategories()
    {
        return self::all();
    }
}
