<?php

namespace App\Providers;

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
    protected $namespace = 'App\Http\Controllers';

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
        //api
        $this->mapApiRoutes();

        //前端-主页
        $this->mapWebRoutes();
        
        //前端-商家
        $this->mapWebBusinessRoutes();
        
        //前端-后台
        $this->mapWebCwadmRoutes();

        //商家后台
        $this->mapBusinessRoutes();
        
        //网站后台
        $this->mapAdminRoutes();
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
             ->group(base_path('routes/web.php'));
    }
    
    protected function mapWebBusinessRoutes()
    {
        Route::prefix('web_business')->middleware('web')
        ->namespace($this->namespace)
        ->group(base_path('routes/webBusiness.php'));
    }
    
    protected function mapWebCwadmRoutes()
    {
        Route::prefix('web_admin')->middleware('web')
        ->namespace($this->namespace)
        ->group(base_path('routes/webAdmin.php'));
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
        Route::prefix('api')->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }
    
    protected function mapAdminRoutes()
    {
        Route::prefix('admin')->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/admin.php'));
    }
    
    protected function mapBusinessRoutes()
    {
        Route::prefix('business')->middleware('api')
        ->namespace($this->namespace)
        ->group(base_path('routes/business.php'));
    }
}
