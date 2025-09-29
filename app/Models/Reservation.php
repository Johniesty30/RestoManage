<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'table_id',
        'reservation_time',
        'guests',
        'status',
    ];

    protected $casts = [
        'reservation_time' => 'datetime',
        'guests' => 'integer',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    // Scopes
    public function scopeToday($query)
    {
        return $query->whereDate('reservation_time', today());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('reservation_time', '>=', now())
                    ->where('status', 'confirmed');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    // Methods
    public function isUpcoming()
    {
        return $this->reservation_time >= now() && $this->status === 'confirmed';
    }

    public function isPast()
    {
        return $this->reservation_time < now();
    }

    public function markAsSeated()
    {
        $this->status = 'seated';
        $this->save();
    }

    public function markAsCompleted()
    {
        $this->status = 'completed';
        $this->save();
    }

    public function markAsCancelled()
    {
        $this->status = 'cancelled';
        $this->save();
    }
}
