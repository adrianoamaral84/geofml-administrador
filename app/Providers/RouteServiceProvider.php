<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

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
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

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

        // Sobrescreve a rota legada de reset administrativo depois do web.php.
        // O URI e o nome permanecem os mesmos para não quebrar links/views existentes,
        // mas a senha nunca mais é alterada diretamente para o CPF.
        $this->mapSecureAdminPasswordResetRoute();
    }

    /**
     * Mantém o mesmo URI e nome usados pela interface administrativa, mas direciona
     * a requisição para o fluxo seguro de redefinição por e-mail.
     */
    protected function mapSecureAdminPasswordResetRoute()
    {
        Route::middleware('web')
            ->get(
                '/admin/users/{id}/reset',
                'App\\Http\\Controllers\\AdminPasswordResetController@send'
            )
            ->name('usuario.reset');
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

    /**
     * Define the "api" routes for the application.
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
