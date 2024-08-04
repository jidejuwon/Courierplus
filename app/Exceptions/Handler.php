<?php

namespace App\Exceptions;

use App\Helpers\blogHelper;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Illuminate\Auth\AuthenticationException;
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

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
          //
        });
    }

    public function render($request, Throwable $e){

        if($e instanceof NotFoundHttpException)
            return blogHelper::errorResponse( $e->getMessage(),'',404);

        if($e instanceof MethodNotAllowedHttpException )
            return blogHelper::errorResponse( 'Method not allowed.','',405);

        if($e instanceof ModelNotFoundException)
            return blogHelper::errorResponse('Resources not found','',404);

        if($e instanceof AuthenticationException)
            if($request->is('api/*'))
                return blogHelper::errorResponse("Given token is invalid or expired",'',401);


        return parent::render($request, $e);
    }
}
