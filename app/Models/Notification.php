<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $table = 'notifications';

    const APPROVED = 'product_approved';
    const STATUS_CHANGE = 'product_status_change';
    const DEPLETED = 'product_depleted';

    public static $types = [self::APPROVED, self::STATUS_CHANGE, self::DEPLETED];

    protected $fillable = [
        'user_id',
        'read_at',
        'type',
        'data',
        'created_at',
        'updated_at'
    ];

    /**
     * @return BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
