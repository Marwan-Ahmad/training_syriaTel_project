<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

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


    public function register(): void
    {
        // معالجة خطأ عدم وجود الموديل (Route Model Binding)
        $this->renderable(function (ModelNotFoundException $e, $request) {
            if ($request->expectsJson() || $request->is('admin/*')) {
                return response()->json([
                    'status' => false,
                    'message' => 'Not Found - The requested resource does not exist.'
                ], 404);
            }
        });

        // معالجة خطأ الرابط أو الـ NotFound بشكل عام
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->expectsJson() || $request->is('admin/*')) {
                return response()->json([
                    'status' => false,
                    'message' => 'Not Found - The requested resource does not exist.'
                ], 404);
            }
        });
    }
}
