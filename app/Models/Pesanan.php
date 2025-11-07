<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pesanan extends Model
{
    protected $fillable = [
        'id_menu',
        'id_pelanggan',
        'id_meja',
        'jumlah',
        'id_user',
    ];

    /**
     * Menu relation.
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'id_menu');
    }

    /**
     * Customer relation.
     */
    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }

    /**
     * Table relation.
     */
    public function meja(): BelongsTo
    {
        return $this->belongsTo(Meja::class, 'id_meja');
    }

    /**
     * User (waiter) relation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Transaction associated with this order.
     */
    public function transaksi(): HasOne
    {
        return $this->hasOne(Transaksi::class, 'id_pesanan');
    }
}
