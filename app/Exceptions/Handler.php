<?php

namespace App\Exceptions;

use App\Traits\ApiResponder;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    use ApiResponder;
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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     * @param \Illuminate\Http\Request $request
     * @param Exception $exception
     * @return \Illuminate\Http\JsonResponse|Response|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof HttpException) {
            $code = $exception->getStatusCode();
            $message = Response::$statusTexts[$code];

            return $this->errorResponse($message, $code);
        }

        if ($exception instanceof ModelNotFoundException) {
            $model = strtolower(class_basename($exception->getModel()));

            return $this->errorResponse(
                "No instance of {$model} exists with the given id",
                Response::HTTP_NOT_FOUND
            );
        }

        if ($exception instanceof AuthorizationException) {
            return $this->errorResponse(
                $exception->getMessage(),
                Response::HTTP_FORBIDDEN
            );
        }

        if ($exception instanceof AuthenticationException) {
            return $this->errorResponse(
                $exception->getMessage(),
                Response::HTTP_UNAUTHORIZED
            );
        }

        if ($exception instanceof ValidationException) {
            $errors = $exception->validator->errors()->getMessages();

            return $this->errorResponse(
                $errors,
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if ( $exception instanceof QueryException) {
            $errors = $exception->getMessage();

            return $this->errorResponse(
                $errors,
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return parent::render($request, $exception);
    }
}
