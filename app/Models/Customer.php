<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $primaryKey = 'customer_id';

    // Sesuaikan fillable dengan kolom yang benar-benar ada di tabel
    protected $fillable = [
        'user_id',
        'email',
        'phone_number',
        'loyalty_points'
        // Hapus 'name' jika kolom tidak ada di tabel
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'customer_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    // Accessor untuk mendapatkan nama dari user
    public function getNameAttribute()
    {
        return $this->user->name;
    }
}
