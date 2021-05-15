<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponser;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if($exception instanceof HttpException){
            $code       = $exception->getStatusCode();
            $message    = Response::$statusTexts[$code];

            return $this->errorResponse($message, $code);
        }

        if($exception instanceof ModelNotFoundException){
            $model      = strtolower(class_basename($exception->getModel()));
            $message    = "Does not exist any instance of {$model} with the given id";

            return $this->errorResponse($message, Response::HTTP_NOT_FOUND);
        }

        if($exception instanceof BadRequestException){
            $message = $exception->getMessage();

            return $this->errorResponse($message, Response::HTTP_BAD_REQUEST);
        }

        if($exception instanceof BusinessLogicException){
            $message = $exception->getMessage();

            return $this->errorResponse($message, Response::HTTP_NOT_FOUND);
        }

        if($exception instanceof ValidationException){
            $errors = $exception->validator->errors()->getMessages();

            return $this->errorResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if(env('APP_DEBUG',false)){
            return parent::render($request, $exception);
        }

        return $this->errorResponse('Unexpected error. Try later', Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
