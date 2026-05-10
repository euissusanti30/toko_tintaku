<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCustomer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'province',
        'city',
        'postal_code',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'customer_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}
