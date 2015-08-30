<?php

namespace t2t2\SuperBravery\Exceptions;

use Exception;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler {

	/**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		HttpException::class,
	];

	/**
	 * Report or log an exception.
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param  \Exception $e
	 *
	 * @return void
	 */
	public function report(Exception $e) {
		return parent::report($e);
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Exception               $e
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Exception $e) {
		if ($request->wantsJson()) {
			if ($e instanceof TokenMismatchException) {
				return response()->json(['error' => 'Session timed out. Please refresh the page.'], 431);
			} elseif (env('APP_DEBUG', false)) {
				return response()->json([
					'error' => 'Server Error: ' . $e
				], 500);
			} else {
				return response()->json(['error' => 'Whoops, looks like something went wrong.'], 500);
			}
		}

		return parent::render($request, $e);
	}
}
