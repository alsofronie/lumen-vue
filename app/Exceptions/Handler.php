<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\Debug\Exception\FlattenException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     * @return void
     * @throws Exception
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        $data = [];
        $status = 0;
        $type = strtoupper(snake_case(class_basename($e)));
        if ($e instanceof HttpResponseException) {
            $status = $e->getResponse()->getStatusCode();
        } elseif ($e instanceof HttpException) {
            $status = $e->getStatusCode();
        } elseif ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
            $status = 404;
        } elseif ($e instanceof AuthorizationException) {
            $status = 403;
        } elseif ($e instanceof AuthenticationException) {
            $status = 401;
        } elseif ($e instanceof ValidationException && $e->getResponse()) {
            $status = 422;
            $data = ['details' => $e->validator->getMessageBag()];
        } elseif ($e instanceof MethodNotAllowedHttpException) {
            $status = 405;
        } else {
            $e = FlattenException::create($e);
        }

        if ($status < 400) {
            $status = 500;
        }

        $error = array_merge([
            'error' => true,
            'type' => $type,
            'status' => $status,
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
        ], $data);

        if (env('APP_DEBUG')) {
            $error = array_merge($error, [
                'debug' => [
                    'line' => $e->getLine(),
                    'file' => $e->getFile(),
                ],
            ]);
            // TODO: add trace to the debug, identify when and why there is a recursion
//            $trace = [];
//            foreach ($e->getTrace() as $t) {
//                $trace[] = [
//                    'file' => $t->file,
//                    'line' => $t->line,
//                ];
//            }
//            $error = array_merge($error, [
//                'trace' => $trace,
//            ]);
        }

        return response()->json($error, $status);
    }
}
