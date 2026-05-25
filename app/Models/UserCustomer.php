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
        'password',
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

    /**
     * Hidden attributes
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Hash password when setting
     */
    public function setPasswordAttribute($value)
    {
        if ($value !== null && $value !== '') {
            $this->attributes['password'] = bcrypt($value);
        }
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
