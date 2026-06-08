<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'selling_price',
        'is_active',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'selling_price' => 'float',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(PackageItem::class);
    }

    public function stock()
    {
        return $this->hasOne(PackageStock::class);
    }

    public static function generateNextCode(): string
    {
        $lastPackage = self::withTrashed()
            ->where('code', 'LIKE', 'PKT-%')
            ->orderBy('code', 'desc')
            ->first();

        if (!$lastPackage) {
            return 'PKT-0001';
        }

        $lastCode = $lastPackage->code;
        $numStr = substr($lastCode, 4);

        if (is_numeric($numStr)) {
            $nextNum = (int)$numStr + 1;
        } else {
            $maxId = self::withTrashed()->max('id') ?? 0;
            $nextNum = $maxId + 1;
        }

        return 'PKT-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
    }
}
