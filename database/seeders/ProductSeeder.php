<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Tạo một Category mặc định nếu chưa có (Để tránh lỗi khóa ngoại category_id)
        // Kiểm tra xem bảng categories có cột 'slug' không, nếu migration categories của bạn đơn giản chỉ có name thì bỏ dòng slug đi.
        // Ở đây mình giả định bảng categories có cấu trúc cơ bản.
        $categoryId = DB::table('categories')->insertOrIgnore([
            'id' => 1,
            'name' => 'Electronics',
            'slug' => 'electronics', // Nếu bảng categories ko có slug, hãy xóa dòng này
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Lấy ID thật để đảm bảo không bị lỗi
        $validCategoryId = DB::table('categories')->first()->id;

        // 2. Xóa dữ liệu cũ để tránh trùng lặp
        DB::table('products')->delete();

        $products = [
            [
                'name' => 'iPhone 15 Pro Max',
                'price' => 1200,
                'image_url' => 'img/product-1.png', // Sửa từ 'image' -> 'image_url'
                'sku' => 'IPHONE-15-PM',            // Sửa từ 'model' -> 'sku'
                'stock_quantity' => 50,             // Thêm trường bắt buộc
                'category_id' => $validCategoryId,  // Thêm trường bắt buộc
                'description' => 'Titanium design, A17 Pro chip, 48MP Main camera.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'MacBook Air M2',
                'price' => 999,
                'image_url' => 'img/product-2.png',
                'sku' => 'MACBOOK-AIR-M2',
                'stock_quantity' => 30,
                'category_id' => $validCategoryId,
                'description' => 'Supercharged by M2. Strikingly thin design.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sony WH-1000XM5',
                'price' => 348,
                'image_url' => 'img/product-3.png',
                'sku' => 'SONY-XM5',
                'stock_quantity' => 100,
                'category_id' => $validCategoryId,
                'description' => 'Industry-leading noise cancellation.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Apple Watch Series 9',
                'price' => 399,
                'image_url' => 'img/product-4.png',
                'sku' => 'WATCH-S9',
                'stock_quantity' => 45,
                'category_id' => $validCategoryId,
                'description' => 'Smarter, brighter, and mightier.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Samsung Galaxy S24',
                'price' => 899,
                'image_url' => 'img/product-5.png',
                'sku' => 'GALAXY-S24',
                'stock_quantity' => 60,
                'category_id' => $validCategoryId,
                'description' => 'Galaxy AI is here.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dell XPS 15 9530',
                'price' => 1400,
                'image_url' => 'img/product-6.png',
                'sku' => 'DELL-XPS-15',
                'stock_quantity' => 20,
                'category_id' => $validCategoryId,
                'description' => 'Immersive display and high performance.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Canon EOS R6 Mark II',
                'price' => 2499,
                'image_url' => 'img/product-7.png',
                'sku' => 'CANON-R6-M2',
                'stock_quantity' => 15,
                'category_id' => $validCategoryId,
                'description' => 'Master of stills and motion.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'iPad Air 5 M1',
                'price' => 559,
                'image_url' => 'img/product-8.png',
                'sku' => 'IPAD-AIR-M1',
                'stock_quantity' => 80,
                'category_id' => $validCategoryId,
                'description' => 'Light. Bright. Full of might.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('products')->insert($products);
    }
}