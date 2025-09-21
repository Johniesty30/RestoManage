<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    protected $primaryKey = 'reservation_id';

    protected $fillable = [
        'customer_id',
        'table_id',
        'reservation_time',
        'number_of_guests',
        'status',
    ];

    protected $casts = [
        'customer_id' => 'integer',
        'table_id' => 'integer',
        'reservation_time' => 'datetime',
        'number_of_guests' => 'integer',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class, 'table_id', 'table_id');
    }
}