<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public $table = 'transaction';

    protected $fillable = [
        'product_id',
        'user_id',
        'created_at',
        'updated_at'
    ];
}
