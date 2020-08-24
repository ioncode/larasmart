![alt text](https://i.gyazo.com/2a06f7790d4c0407a345ea86ad6e7eaa.png)

## LaraSmart 

This app contains demo for use OpenFoodFacts API

- [OpenFoodFacts Wiki](https://wiki.openfoodfacts.org/API).
- [Food data](https://world.openfoodfacts.org/data).
- ProductSearch [module](https://github.com/ioncode/larasmart/tree/master/core/Modules/ProductSearch) contains all stuff.
- Required dependencies described in  [composer.json](https://github.com/ioncode/larasmart/blob/master/core/composer.json).
- Install Laravel as usual, don't forget [migrations](https://laravel.com/docs/migrations).
- Start dev server "php artisan serve" 
- [Visit Module page](http://127.0.0.1:8000/productsearch/invoke?product_name=Nut&page=1) "/productsearch/invoke".
- Run tests "php artisan test --filter Modules".

