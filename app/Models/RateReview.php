<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RateReview extends Model
{
    protected $table = 'rate_reviews';
    // use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'review',
        'updated_at',
        'created_at,'
    ];
}
