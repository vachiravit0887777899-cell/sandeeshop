<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInventory extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'box_item_id', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function boxItem()
    {
        return $this->belongsTo(BoxItem::class);
    }
}