<?php

namespace App\Providers;

use App\Model\backend\Setting;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('brandlogo', function () {
            $setting = Setting::find(1);
            if($setting && @$setting->img_path && File::exists(public_path("uploads/thumbnail/{$setting->img_path}")))
            {
                return "<img class='brand_logo' title='no img' src='".asset("uploads/thumbnail/{$setting->img_path}")."' />";
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
