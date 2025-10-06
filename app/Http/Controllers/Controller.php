<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
  use AuthorizesRequests, ValidatesRequests;
  protected function success($message = '成功', $data = null,  $code = 200)
  {
    return response()->json([
      'status' => true,
      'message' => $message,
      'data' => $data
    ], $code);
  }

  // 失敗回傳
  protected function error($message = '失敗', $errors = [], $code = 400)
  {
    return response()->json([
      'status' => false,
      'message' => $message,
      'errors' => $errors
    ], $code);
  }
}
