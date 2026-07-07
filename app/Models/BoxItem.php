<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoxItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'box_id', 'name', 'image', 'rarity',
        'market_value', 'probability', 'stock',
    ];

    public function box()
    {
        return $this->belongsTo(Box::class);
    }

    public function inventories()
    {
        return $this->hasMany(UserInventory::class);
    }
}