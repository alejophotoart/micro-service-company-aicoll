<?php

use App\Console\Commands\LogsDeleteCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withSchedule( function (Schedule $schedule) {
        $schedule->command( LogsDeleteCommand::class )->dailyAt('00:00');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        
        $exceptions->render( function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                
                Log::error($e);
                return response()->json([
                    'message' => 'El recurso solicitado no fue encontrado.'
                ], 404);
            }
        });

    })->create();
