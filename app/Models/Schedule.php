<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $primaryKey = 'schedule_id';
    protected $fillable = ['staff_id', 'start_time', 'end_time'];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
