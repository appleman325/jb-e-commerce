<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProduct extends Model
{
    public $table = 'users_products';

    const PENDING = 'pending';
    const APPROVED = 'approved';
    const REJECTED = 'rejected';

    public static $statuses = [self::PENDING, self::APPROVED, self::REJECTED];

    protected $fillable = [
        'user_id',
        'product_id',
        'status',
        'created_at',
        'updated_at'
    ];
}
