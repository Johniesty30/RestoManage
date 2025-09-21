<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shift extends Model
{
    use HasFactory;

    protected $primaryKey = 'shift_id';

    protected $fillable = [
        'staff_id',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'staff_id' => 'integer',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'staff_id');
    }
}