<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'stock',
        // Add other fields as needed
    ];
    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    /**
     * Get the order items for the product.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    /**
     * Get the cart items for the product.
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
    /**
     * Get the reviews for the product.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
