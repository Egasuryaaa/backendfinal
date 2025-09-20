<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
use App\Console\Commands\CancelExpiredOrders;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * All commands that should be loaded by the application.
     */
    protected $commands = [
        CancelExpiredOrders::class,
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register console commands
        if ($this->app->runningInConsole()) {
            $this->commands($this->commands);
        }

        // Format response konsisten untuk API
        Response::macro('apiSuccess', function ($data, $message = 'Operasi berhasil', $statusCode = 200) {
            return Response::json([
                'success' => true,
                'message' => $message,
                'data' => $data
            ], $statusCode);
        });

        Response::macro('apiError', function ($message, $errors = null, $statusCode = 400) {
            $response = [
                'success' => false,
                'message' => $message,
            ];

            if (!is_null($errors)) {
                $response['errors'] = $errors;
            }

            return Response::json($response, $statusCode);
        });
    }
}
