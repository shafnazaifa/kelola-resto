<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meja extends Model
{
    // Status constants
    const STATUS_TERSEDIA = 'tersedia';
    const STATUS_DIISI = 'diisi';
    
    protected $fillable = [
        'nomer_meja',
        'kursi',
        'status',
    ];
    
    /**
     * Check if table is available for new orders.
     */
    public function isAvailable(): bool
    {
        return $this->status === self::STATUS_TERSEDIA;
    }
    
    /**
     * Check if table is occupied.
     */
    public function isOccupied(): bool
    {
        return $this->status === self::STATUS_DIISI;
    }

    /**
     * Get all orders (pesanan) for this table.
     */
    public function pesanans(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'id_meja');
    }
}
