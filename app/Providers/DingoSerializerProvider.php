<?php

namespace App\Providers;

use App\Serializer\DataArraySerializer;
use Dingo\Api\Transformer\Adapter\Fractal;
use Illuminate\Support\ServiceProvider;
use League\Fractal\Manager;

class DingoSerializerProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['Dingo\Api\Transformer\Factory']->setAdapter(function ($app) {
            $fractal = new Manager();
            $fractal->setSerializer(new DataArraySerializer);
            return new Fractal($fractal);
        });
    }
}
