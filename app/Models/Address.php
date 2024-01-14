<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $table = 'address';

    protected $fillable = [
        'cep',
        'street',
        'number',
        'district',
        'city',
        'state'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function getFullAddressAttribute() {
        return $this->street . ', ' . $this->number . ', ' . $this->district . ' - ' . $this->city . ', ' . $this->state . ' - ' . $this->cep;
    }
}
