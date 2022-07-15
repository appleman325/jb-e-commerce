<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    const ACTIVE = 'active';
    const ON_HOLD = 'on-hold';
    const EXPIRED = 'expired';

    public static $statuses = [self::ACTIVE, self::ON_HOLD, self::EXPIRED];

    public $table = 'products';

    protected $fillable = [
        'status',
        'monthly_inventory',
        'created_at',
        'updated_at'
    ];

    /**
     * @return belongsToMany
     **/
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
