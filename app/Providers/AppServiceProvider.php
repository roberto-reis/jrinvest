<?php

namespace App\Providers;

use Inertia\Inertia;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Domain\Main\Interfaces\ICarteiraRepository;
use App\Domain\Carteira\Repositories\CarteiraRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * @return void
     */
    public function register()
    {
        Inertia::share([
            'flash' => function () {
                return [
                    'success' => Session::get('success'),
                    'error' => Session::get('error'),
                ];
            },
        ]);

        $this->app->bind(ICarteiraRepository::class, CarteiraRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->paginarCollection();
    }

    /**
     * Paginate a standard Laravel Collection.
     * @param int $perPage
     * @param int $total
     * @param int $page
     * @param string $pageName
     * @return array
     */
    private function paginarCollection()
    {
        Collection::macro('paginate', function($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });
    }
}
