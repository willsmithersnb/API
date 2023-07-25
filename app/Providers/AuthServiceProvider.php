<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\Catalog;
use App\Models\Customer;
use App\Models\Firmware;
use App\Models\Ingredient;
use App\Models\Message;
use App\Models\MessageThread;
use App\Models\Device;
use App\Models\Documentation;
use App\Models\FileUpload;
use App\Models\Item;
use App\Models\Material;
use App\Models\NBAI\Recommendation;
use App\Models\NBAI\Seat;
use App\Models\Order;
use App\Models\PackagingOption;
use App\Models\PricingAddon;
use App\Models\PricingAddonTier;
use App\Models\PricingRule;
use App\Models\QcTest;
use App\Policies\CustomerPolicy;
use App\Policies\DevicePolicy;
use App\Policies\FileUploadPolicy;
use App\Policies\FirmwarePolicy;
use App\Policies\IngredientPolicy;
use App\Policies\MaterialPolicy;
use App\Policies\MessagePolicy;
use App\Policies\MessageThreadPolicy;
use App\Policies\QcTestPolicy;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\QcTestMethod;
use App\Models\Quote;
use App\Models\UserInvite;
use App\Policies\ItemListPolicy;
use App\Policies\CartPolicy;
use App\Policies\CatalogPolicy;
use App\Policies\DocumentationPolicy;
use App\Policies\ItemPolicy;
use App\Policies\OrderPolicy;
use App\Policies\PackagingOptionPolicy;
use App\Policies\PricingAddonPolicy;
use App\Policies\PricingAddonTierPolicy;
use App\Policies\PricingRulePolicy;
use App\Policies\ProductPolicy;
use App\Policies\ProductTypePolicy;
use App\Policies\QcTestMethodPolicy;
use App\Policies\QuotePolicy;
use App\Policies\RecommendationPolicy;
use App\Policies\SeatPolicy;
use App\Policies\UserInvitePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Device::class => DevicePolicy::class,
        Firmware::class => FirmwarePolicy::class,
        Ingredient::class => IngredientPolicy::class,
        Seat::class => SeatPolicy::class,
        Recommendation::class => RecommendationPolicy::class,
        MessageThread::class => MessageThreadPolicy::class,
        Message::class => MessagePolicy::class,
        FileUpload::class => FileUploadPolicy::class,
        QcTest::class => QcTestPolicy::class,
        Material::class => MaterialPolicy::class,
        Customer::class => CustomerPolicy::class,
        Product::class => ProductPolicy::class,
        ProductType::class => ProductTypePolicy::class,
        Item::class => ItemPolicy::class,
        QcTestMethod::class => QcTestMethodPolicy::class,
        PackagingOption::class => PackagingOptionPolicy::class,
        ItemList::class => ItemListPolicy::class,
        Order::class => OrderPolicy::class,
        Cart::class => CartPolicy::class,
        Quote::class => QuotePolicy::class,
        PricingRule::class => PricingRulePolicy::class,
        Documentation::class => DocumentationPolicy::class,
        UserInvite::class => UserInvitePolicy::class,
        Catalog::class => CatalogPolicy::class,
        PricingAddon::class => PricingAddonPolicy::class,
        PricingAddonTier::class => PricingAddonTierPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::provider('authUserProvider', function ($app, array $config) {
            return new AuthUserProvider($app->make('hash'), $config['model']);
       });
    }
}
