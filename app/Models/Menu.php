<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $fillable = [
        'name_menu',
        'harga',
    ];

    /**
     * Get all orders (pesanan) for this menu item.
     */
    public function pesanans(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'id_menu');
    }
}
