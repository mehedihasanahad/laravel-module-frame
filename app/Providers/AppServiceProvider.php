<?php

namespace App\Providers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

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
        $this->loadCoreModules();
    }

    private function loadCoreModules()
    {
        $coreModulesPath = app_path('Core');
        $filesystem = new Filesystem();
        $coreModulePaths = array_map('basename', $filesystem->directories($coreModulesPath));
        $coreModulesBasePath = base_path('app/Core');
        foreach ($coreModulePaths as $moduleName){
            $this->loadResources($coreModulesBasePath,$moduleName);
        }
    }

    private function loadResources($modulePath,$moduleName){

        $routesFilePath = $modulePath .'/'.$moduleName. '/routes/web.php';
        $viewsDirectoryPath = $modulePath .'/'.$moduleName. '/resources/views';
        $translationsDirectoryPath = $modulePath .'/'.$moduleName. '/resources/lang';

        if (file_exists($routesFilePath)) {
            include $routesFilePath;
        }

        if (is_dir($viewsDirectoryPath)) {
            $this->loadViewsFrom($viewsDirectoryPath, $moduleName);
        }

        if (is_dir($translationsDirectoryPath)) {
            $this->loadTranslationsFrom($translationsDirectoryPath, $moduleName);
        }
    }


}
