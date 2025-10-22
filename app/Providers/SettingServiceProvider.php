<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File; // <-- Tambahkan ini

class SettingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    
    public function boot(): void
    {
        // Gunakan view composer untuk membagikan data settings ke layout utama
        View::composer('layouts.app', function ($view) {
            $path = storage_path('app/settings.json');
            $settings = [];

            if (File::exists($path)) {
                $settings = json_decode(File::get($path), true);
            }

            // Atur nilai default jika kunci tidak ada
            $defaultSettings = [
                'company_name' => 'Tohjaya.contractor',
                'company_logo' => null,
            ];

            // Gabungkan data dari file dengan data default
            $viewData = array_merge($defaultSettings, $settings);

            $view->with('globalSettings', $viewData);
        });
    }
}