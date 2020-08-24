<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 24.08.2020
 * Time: 3:27
 */

namespace Modules\ProductSearch\Tests\Unit;

use Modules\ProductSearch\Entities\Product;
use Tests\TestCase;

class ProductTest extends TestCase
{
    /**
     * Search existing product with the same external_id, create & check unique index, delete test product after all
     * @param string $external_id
     * @throws \Exception
     */
    public function testProductSaveUnique($external_id = '777unrealId')
    {
        $this->assertFalse(!empty(Product::where('external_id', $external_id)->first()), "Product with test id $external_id already exists");
        $fields = [
            'external_id' => $external_id,
            'product_name' => 'Test product',
            'categories' => 'unit test',
            'image_url' => null,
        ];
        $product = new Product($fields);
        $this->assertTrue($product->save());
        $this->assertDatabaseHas('products', $fields);
        $productNotUniqueId = new Product($fields);
        try {
            $productNotUniqueId->save();
        } catch (\Exception $exception) {
            $this->assertNotEmpty($exception);
        }
        $this->assertTrue($product->delete());
        $this->assertDeleted('products', $fields);
        echo 'Done' . PHP_EOL;
    }
}
