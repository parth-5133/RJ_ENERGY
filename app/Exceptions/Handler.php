<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use PDOException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        // Check if the exception is a 404 error (Not Found)
        if ($exception instanceof NotFoundHttpException) {
            return response()->view('errors.404', [], 404); // Render your custom 404 page
        }

        // if ($exception instanceof PDOException) {
        //     return response()->view('errors.database', [], 500);
        // }

        // Default behavior for other exceptions
        return parent::render($request, $exception);
    }
}
