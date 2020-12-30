<?php

namespace PMW\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

Collection::macro('paginate', function( $perPage, $total = null, $page = null, $pageName = 'page' ) {
    $page = $page ?: LengthAwarePaginator::resolveCurrentPage( $pageName );

    return new LengthAwarePaginator( $this->forPage( $page, $perPage ), $total ?: $this->count(), $perPage, $page, [
        'path' => LengthAwarePaginator::resolveCurrentPath(),
        'pageName' => $pageName,
    ]);
});

class AppServiceProvider extends ServiceProvider
{

    /**
     * Jenis storage yang diinginkan
     * Ada dua jenis, yaitu 'local' dan 'googledrive'
     * silahkan di ganti sesuai selera
     *
     * @var string
     */
    private $storage = 'local';

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Carbon\Carbon::setlocale('id');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('ExcelExport','PMW\Support\ExcelExport');
        $this->app->bind('Dana','PMW\Support\Dana');
        $this->app->bind('FileHandler', 'PMW\Support\FileHandler\GoogleDrive');
    }

}
