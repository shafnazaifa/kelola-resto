<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    protected $fillable = [
        'id_pesanan',
        'total',
        'bayar',
    ];

    /**
     * Pesanan relation.
     */
    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan');
    }

    /**
     * Get the menu through pesanan relation.
     */
    public function menu()
    {
        return $this->hasOneThrough(Menu::class, Pesanan::class, 'id', 'id', 'id_pesanan', 'id_menu');
    }

    /**
     * Get the customer through pesanan relation.
     */
    public function pelanggan()
    {
        return $this->hasOneThrough(Pelanggan::class, Pesanan::class, 'id', 'id', 'id_pesanan', 'id_pelanggan');
    }

    /**
     * Get the table through pesanan relation.
     */
    public function meja()
    {
        return $this->hasOneThrough(Meja::class, Pesanan::class, 'id', 'id', 'id_pesanan', 'id_meja');
    }

    /**
     * Get the user through pesanan relation.
     */
    public function user()
    {
        return $this->hasOneThrough(User::class, Pesanan::class, 'id', 'id', 'id_pesanan', 'id_user');
    }
}
