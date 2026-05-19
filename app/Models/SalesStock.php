<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesStock extends Model
{
    protected $fillable = ['user_id', 'product_id', 'qty'];

    /** Sales person yang memegang stok ini */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Produk yang dipegang */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
