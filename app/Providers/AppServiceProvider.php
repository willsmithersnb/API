<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Adds a readable morphMap
        Relation::morphMap([
            'cart' => 'App\Models\Cart',
            'order' => 'App\Models\Order',
            'quote' => 'App\Models\Quote',
            'packaging_option' => 'App\Models\PackagingOption',
            'ingredient' => 'App\Models\Ingredient',
            'seat' => 'App\Models\Seat'
        ]);

        // Macros
        Collection::macro('firstOrFail', function ($key, $value) {
            return $this->where($key, $value)->first() ?: abort(404);
        });

        // Validators
        /**
         * Custom validator to confirm if the polymorph related table has the object available.
         * @param Integer $value
         * @param Morphed Object $type
         * @param Illuminate\Support\Facades\Validator $validator
         * @param => extending validator
         */
        Validator::extend('poly_exists', function ($attribute, $value, $parameters, $validator) {
            if (!$type = \Arr::get($validator->getData(), $parameters[0], false)) {
                return false;
            }

            if (Relation::getMorphedModel($type)) {
                $type = Relation::getMorphedModel($type);
            }

            if (!class_exists($type)) {
                return false;
            }

            return !empty(resolve($type)->find($value));
        }, "Could not find object");
    }
}
