<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
   * storage/logs/laravel.log
   */
  public function register(): void
  {
    $this->reportable(function (Throwable $e) {
      //
      \Log::error($e);
    });
  }



  public function render($request, Throwable $exception)
  {
    // 驗證錯誤
    if ($exception instanceof ValidationException) {
      return response()->json([
        'status' => false,
        'message' => '資料驗證失敗',
        'errors' => $exception->errors()
      ], 422);
    }

    // 模型找不到
    if ($exception instanceof ModelNotFoundException) {
      return response()->json([
        'status' => false,
        'message' => '找不到資料',
      ], 404);
    }

    // 其他 HTTP 錯誤
    if ($exception instanceof HttpException) {
      return response()->json([
        'status' => false,
        'message' => $exception->getMessage() ?: '發生錯誤',
      ], $exception->getStatusCode());
    }

    // 其他 Exception
    return response()->json([
      'status' => false,
      'message' => $exception->getMessage(),
    ], 500);
  }
}
