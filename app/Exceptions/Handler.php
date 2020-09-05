<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
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
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
		if ($request->wantsJson()) {
			$response = get_class($exception) === 'Illuminate\Database\Eloquent\ModelNotFoundException' 
				? [
					'errors' => 'Model not found.'
				]
				: [
					'errors' => 'An error occured.'
				];

			if (config('app.debug')) {
				$response['exception'] = get_class($exception);
				$response['message'] = $exception->getMessage();
				$response['trace'] = $exception->getTrace();
			}

			$status = get_class($exception) === 'Illuminate\Database\Eloquent\ModelNotFoundException' 
				? 404
				: 400;

			if ($this->isHttpException($exception)) {
				// Grab the HTTP status code from the Exception
				$status = $exception->getStatusCode();
			}

			return response()->json($response, $status);
		}

		return parent::render($request, $exception);
    }
}
