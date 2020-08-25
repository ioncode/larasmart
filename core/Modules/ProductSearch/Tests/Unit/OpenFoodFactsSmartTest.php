<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 24.08.2020
 * Time: 2:11
 */

namespace Modules\ProductSearch\Tests\Unit;

use Modules\ProductSearch\Entities\Facades\OpenFoodFactsSmart;
use Tests\TestCase;

class OpenFoodFactsSmartTest extends TestCase
{

    /**
     * Test OpenFoodFactsSmart facade & method findOnPage by calling full page
     * @param int $fullPageLength
     */
    public function testFindOnPage($fullPageLength = 20)
    {
        $page = OpenFoodFactsSmart::findOnPage('Cola', 1, $fullPageLength);
        $this->assertTrue($page->count() === $fullPageLength);
        foreach ($page as $product) {
            $this->assertInstanceOf('Modules\ProductSearch\Entities\Product', $product, 'Method must return items of type Product');
            $this->assertArrayHasKey('external_id', (array)$product->getAttributes(), 'There is no needed external_id attribute to store id of OpenFoodFacts Api');
            $this->assertNotEmpty($product->external_id, 'external_id can not be blank');
        }
    }
}
