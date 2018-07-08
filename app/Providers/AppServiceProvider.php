<?php

namespace App\Providers;

use App\Observers\QueryLoggingObservers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        
        $this->queryLogObserversInclude();
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

    private function queryLogObserversInclude()
    {
        $modelClassNames = $this->listModelClassNamesInFolder(app_path('Models'));
        
        foreach ($modelClassNames as $name) {
            $name::observe(QueryLoggingObservers::class);
        }

        \App\User::observe(QueryLoggingObservers::class);
    }

    protected function listModelClassNamesInFolder($dir)
    {
        $modelClassNames = [];
        $folderFileNames = array_diff( scandir($dir), ['.', '..']);

        foreach ($folderFileNames as $name) {

            if(is_dir($dir.'/'.$name)) 
                $modelClassNames = array_merge(
                    $modelClassNames, 
                    $this->listModelClassNamesInFolder($dir.'/'.$name)
                );
            else
                $modelClassNames[] = 'App\\Models\\'.str_replace('.php', '', $name);
        }

        return $modelClassNames;
    }
}
