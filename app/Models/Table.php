<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Table extends Model
{
    use HasFactory;

    protected $primaryKey = 'table_id';

    protected $fillable = [
        'table_number',
        'capacity',
        'status',
    ];

    protected $casts = [
        'capacity' => 'integer',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'table_id', 'table_id');
    }
}