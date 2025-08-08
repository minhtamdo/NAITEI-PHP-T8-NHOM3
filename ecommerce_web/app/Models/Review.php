<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'image_url',
        'comment',
    ];
    /**
     * Get the user that owns the review.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Get the product that is reviewed.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
