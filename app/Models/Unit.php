<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'code', 'is_active'];

    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function rawMaterials(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RawMaterial::class);
    }
}
