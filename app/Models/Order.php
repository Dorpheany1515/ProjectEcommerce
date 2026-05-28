<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // 💡 ត្រូវតែជាតារាង orders
    protected $table = 'orders';

    protected $fillable = [
        'order_number', 'user_id', 'total_amount', 'status', 
        'payment_method', 'payment_id', 'name', 'email', 'phone', 'address'
    ];

    // 💡 Order មួយ មាន Items ច្រើន
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }
}