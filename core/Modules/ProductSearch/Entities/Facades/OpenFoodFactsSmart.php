<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 08.08.2020
 * Time: 10:26
 */

namespace Modules\ProductSearch\Entities\Facades;

use Illuminate\Support\Facades\Facade;

class OpenFoodFactsSmart extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'openfoodfactssmart';
    }
}