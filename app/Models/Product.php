<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table= 'product';
    protected $fillable = [
        'product_name',
        'qty',
        'regular_price',
        'sale_price',
        'cate_id',
        'size',
        'color',
        'description',
        'image',
        'user_id',
    ];
   public function category() {
    return $this->belongsTo(category::class, 'cate_id', 'id'); 
    }
}
