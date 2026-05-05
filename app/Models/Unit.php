<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = ['name', 'code'];

    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function rawMaterials(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RawMaterial::class);
    }
}
