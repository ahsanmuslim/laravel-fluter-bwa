<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductGallery;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //data seeder user
        User::create([
            'name' => 'Susanto',
            'email' => 'susanto@gmail.com',
            'username' => 'susanto',
            'roles' => 'admin',
            'phone' => '081280077343',
            'password' => bcrypt('password')
        ]);

        User::create([
            'name' => 'Ahsan',
            'email' => 'ahsan@gmail.com',
            'username' => 'ahsan',
            'roles' => 'user',
            'phone' => '081280077343',
            'password' => bcrypt('password')
        ]);

        //data seeder kategori
        ProductCategory::create([
            'name' => 'Sepatu'
        ]);

        ProductCategory::create([
            'name' => 'Pakaian'
        ]);

        //data seeder produk
        Product::create([
            'name' => 'Sepatu Adidas Combo',
            'description' => 'Sepatu model terbaru dari Adidas untuk olahraga',
            'price' => 100000,
            'categories_id' => 1,
            'tags' => 'sepatu olahraga'
        ]);

        Product::create([
            'name' => 'Sepatu Nike Cristiano',
            'description' => 'Sepatu model khusus untuk Cristiano Ronaldo',
            'price' => 200000,
            'categories_id' => 1,
            'tags' => 'sepatu ronaldo'
        ]);

        Product::create([
            'name' => 'Kaos Jersey Arsenal Saka',
            'description' => 'Jersey kaos home tim Arsenal original bro',
            'price' => 75000,
            'categories_id' => 2,
            'tags' => 'jersey'
        ]);

        //data seeder produk gallery
        ProductGallery::create([
            'products_id' => 1,
            'url' => 'gambarsepatuadidas1'
        ]);
        
        ProductGallery::create([
            'products_id' => 1,
            'url' => 'gambarsepatuadidas2'
        ]);
        
        ProductGallery::create([
            'products_id' => 2,
            'url' => 'gambarsepatunike'
        ]);
        
        ProductGallery::create([
            'products_id' => 3,
            'url' => 'gambarjerseyarsenal'
        ]);
        
        //dta seeder transaction
        Transaction::create([
            'user_id' => 1,
            'address' => 'Tangerang',
            'total_price' => 300000,
            'payment' => 'MANUAL',
            'shipping_price' => 10000,
            'status' => 'PENDING'
        ]);

        Transaction::create([
            'user_id' => 2,
            'address' => 'Serang',
            'total_price' => 275000,
            'payment' => 'MANUAL',
            'shipping_price' => 15000,
            'status' => 'PENDING'
        ]);

        //dta seeder item transaction
        TransactionItem::create([
            'users_id' => 1,
            'products_id' => 1,
            'transactions_id' => 1,
            'quantity' => 1
        ]);
        TransactionItem::create([
            'users_id' => 1,
            'products_id' => 2,
            'transactions_id' => 1,
            'quantity' => 1
        ]);

        TransactionItem::create([
            'users_id' => 2,
            'products_id' => 2,
            'transactions_id' => 2,
            'quantity' => 1
        ]);
        TransactionItem::create([
            'users_id' => 2,
            'products_id' => 3,
            'transactions_id' => 2,
            'quantity' => 1
        ]);


    }
}
