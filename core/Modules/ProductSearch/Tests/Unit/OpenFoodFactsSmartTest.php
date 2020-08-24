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
        $this->assertTrue(OpenFoodFactsSmart::findOnPage('Cola', 1, $fullPageLength)->count() === $fullPageLength);
    }
}
