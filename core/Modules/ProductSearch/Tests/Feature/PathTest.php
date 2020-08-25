<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 24.08.2020
 * Time: 0:28
 */

namespace Modules\ProductSearch\Tests\Feature;

use Tests\TestCase;

class PathTest extends TestCase
{
    public function testInvokePathTest()
    {
        $response = $this->get('/productsearch/invoke');

        $response->assertStatus(200);
    }

    /**
     * This test is not about OpenFoodFacts Api (see corresponding Unit test below), this is about path, route & response code
     */
    public function testMars()
    {
        $response = $this->get('/productsearch/invoke?product_name=mars&page=2');

        $response->assertStatus(200);
    }

    public function testFake()
    {
        $response = $this->get('/productsearch/fake');

        $response->assertStatus(404);
    }
}
