<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;

class ShopFilterTest extends TestCase
{
    use RefreshDatabase; 

    protected $categoryElectronics;
    protected $categoryFurniture;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Tạo dữ liệu mẫu
        $this->categoryElectronics = Category::factory()->create(['name' => 'Electronics']);
        $this->categoryFurniture = Category::factory()->create(['name' => 'Furniture']);

        Product::factory()->create([
            'name' => 'iPhone 15 Pro',
            'price' => 1000,
            'category_id' => $this->categoryElectronics->id,
            'created_at' => now()->subDays(5)
        ]);

        Product::factory()->create([
            'name' => 'Samsung Galaxy S24',
            'price' => 900,
            'category_id' => $this->categoryElectronics->id,
            'created_at' => now()->subDays(2)
        ]);

        Product::factory()->create([
            'name' => 'Wooden Chair',
            'price' => 50,
            'category_id' => $this->categoryFurniture->id,
            'created_at' => now()->subDays(10)
        ]);
    }

    /** @test */
    public function it_can_search_products_by_keyword()
    {
        $response = $this->get(route('shop.index', ['keyword' => 'iPhone']));
        $response->assertStatus(200);
        $response->assertSee('iPhone 15 Pro');
        $response->assertDontSee('Wooden Chair');
    }

    /** @test */
    public function it_can_filter_products_by_category()
    {
        $response = $this->get(route('shop.index', ['category' => $this->categoryFurniture->id]));
        $response->assertStatus(200);
        $response->assertSee('Wooden Chair');
        $response->assertDontSee('iPhone 15 Pro');
    }

    /** @test */
    public function it_can_filter_products_by_price_range()
    {
        $response = $this->get(route('shop.index', ['min_price' => 800, 'max_price' => 950]));
        $response->assertStatus(200);
        $response->assertSee('Samsung Galaxy S24'); 
        $response->assertDontSee('iPhone 15 Pro');
    }

    /** @test */
    public function it_can_sort_products_by_price_ascending()
    {
        $response = $this->get(route('shop.index', ['sort' => 'price_asc']));
        $response->assertStatus(200);
        $response->assertSeeInOrder(['Wooden Chair', 'Samsung Galaxy S24', 'iPhone 15 Pro']);
    }

    /** @test */
    public function pagination_links_should_preserve_query_parameters()
    {
        Product::factory()->count(15)->create([
            'name' => 'iPhone Clone',
            'category_id' => $this->categoryElectronics->id
        ]);

        $response = $this->get(route('shop.index', ['keyword' => 'iPhone']));
        $response->assertStatus(200);
        
        // Kiểm tra logic appends()
        $response->assertSee('keyword=iPhone');
    }
}