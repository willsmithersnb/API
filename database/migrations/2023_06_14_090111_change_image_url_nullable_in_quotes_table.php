<?php

use App\Models\Quote;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeImageUrlNullableInQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->text('image_url')->nullable()->change();
        });

        $quotes = Quote::unScoped()->get();
        foreach ($quotes as $quote) {
            $itemPackagingOptions = $quote->itemList()->unScoped()->first()->items()->unScoped()->first()->itemPackagingOptions()->unScoped()->get();
            foreach ($itemPackagingOptions as $itemPackagingOption) {
                $packagingType = $itemPackagingOption->packagingOption()->unScoped()->withTrashed()->first()->packaging_type;
                if ($packagingType == 'Pod') {
                    $quote->image_url = null;
                }
            }
            $quote->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $quotes = Quote::unScoped()->get();
        foreach ($quotes as $quote) {
            $quote->image_url = 'https://nb-lux-api-staging.s3.amazonaws.com/local/temp/product/a3c658e5-268a-4f67-b004-8ee7c2eccce6__nb-white';
            $quote->save();
        }
        Schema::table('quotes', function (Blueprint $table) {
            $table->text('image_url')->nullable(false)->change();
        });
    }
}
