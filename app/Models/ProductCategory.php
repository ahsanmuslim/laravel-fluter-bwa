<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use HasFactory, SoftDeletes;

    //field kolom ynag boleh di isi
    protected $fillable = [
        'name'
    ];

    //membuat relationship antar table yaitu tabel produk dan tabel categori
    public function products ()
    {
        return $this->hasMany(Product::class, 'categories_id', 'id');
    }

}
