<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    // 💡 ត្រូវតែជាតារាង order_items
    protected $table = 'order_items';

    protected $fillable = [
        'order_id', 
        'product_id', 
        'quantity', 
        'price'
    ];

    // 💡 Item នីមួយៗ ជាកម្មសិទ្ធិរបស់ Order តែមួយ
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    // 💡 Item នីមួយៗ ភ្ជាប់ទៅកាន់ផលិតផល (Product) តែមួយ
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}