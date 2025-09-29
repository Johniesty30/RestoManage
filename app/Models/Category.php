<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    // Relationships
    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }

    // Scope for active menu items count
    public function scopeWithActiveMenuItems($query)
    {
        return $query->withCount(['menuItems' => function ($query) {
            $query->where('is_available', true);
        }]);
    }
}
