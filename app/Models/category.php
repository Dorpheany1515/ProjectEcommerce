<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    use HasFactory;

    // 💡 ឈ្មោះតារាងនៅក្នុង Database របស់អ្នក
    protected $table = 'category';

    protected $fillable = ['category_name'];
}
 