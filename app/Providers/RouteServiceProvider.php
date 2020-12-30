<?php

namespace PMW\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'PMW\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web/web.php'));
        
        /**
         * Berikut adalah route untuk melakukan testing
         * pastikan konfigurasi APP_STATUS bukan deployed jika ingin
         * menjalankan route ini
         */
//        if($_ENV['APP_STATUS'] != 'deployed'){
//            Route::middleware('web')
//                ->namespace($this->namespace)
//                ->group(base_path('routes/web/tes/yusuf.php'));
//            Route::middleware('web')
//                ->namespace($this->namespace)
//                ->group(base_path('routes/web/tes/bagas.php'));
//            Route::middleware('web')
//                ->namespace($this->namespace)
//                ->group(base_path('routes/web/tes/rafy.php'));
//        }
        
        /**
         * Berikut adalah route berdasarkan jenis role
         * sehingga route tidak perlu berada pada satu file
         */

        Route::middleware(['web','profil','auth','mahasiswa'])
            ->namespace($this->namespace)
            ->group(base_path('routes/web/mahasiswa.php'));

        Route::middleware(['web','profil','auth','ketuatim'])
            ->namespace($this->namespace)
            ->group(base_path('routes/web/ketua.php'));

        Route::middleware(['web','profil','auth','dosen'])
            ->namespace($this->namespace)
            ->group(base_path('routes/web/dosen.php'));

        Route::middleware(['web','profil','auth','reviewer'])
            ->namespace($this->namespace)
            ->group(base_path('routes/web/reviewer.php'));

        Route::middleware(['web','profil','auth','adminfakultas'])
            ->namespace($this->namespace)
            ->group(base_path('routes/web/adminfakultas.php'));

        Route::middleware(['web','profil','auth','adminuniv'])
            ->namespace($this->namespace)
            ->group(base_path('routes/web/adminuniv.php'));

        Route::middleware(['web','profil','auth','superadmin'])
            ->namespace($this->namespace)
            ->group(base_path('routes/web/superadmin.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
