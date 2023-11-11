<?php

namespace App\Exceptions;

use App\Http\Responses\V1\InfoResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $this->renderable(function (ResourceNotFoundException $resourceNotFoundException, Request $request) {
            return new InfoResponse($resourceNotFoundException->getMessage(), Response::HTTP_NOT_FOUND);
        });
        $this->renderable(function (ResourceConflictException $resourceNotFoundException, Request $request) {
            return new InfoResponse($resourceNotFoundException->getMessage(), Response::HTTP_CONFLICT);
        });
    }
}
