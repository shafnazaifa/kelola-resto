<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pelanggan extends Model
{
    protected $fillable = [
       'name_pelanggan',
       'gender',
       'phone_number',
       'address',
    ];

    protected $casts = [
        'gender' => 'boolean',
    ];

    /**
     * Get all orders (pesanan) for this customer.
     */
    public function pesanans(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'id_pelanggan');
    }
}
