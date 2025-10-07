<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class CaptchaController extends Controller
{
  //
  // 參數：長度、寬高、過期秒數
  protected $length = 5;
  protected $width = 160;
  protected $height = 50;
  protected $ttl = 300; // 5 minutes

  public function get()
  {
    // 產生隨機文字（只用大寫/數字，避免容易混淆字元）
    // $chars = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';
    $chars = '1';
    $text = '';
    for ($i = 0; $i < $this->length; $i++) {
      $text .= $chars[random_int(0, strlen($chars) - 1)];
    }

    // 產生 key 並存在 cache（用於 stateless API）
    $key = (string) Str::uuid();
    Cache::put('captcha_' . $key, strtolower($text), $this->ttl);

    // 產生圖片（GD）
    $image = imagecreatetruecolor($this->width, $this->height);

    // 顏色
    $bg = imagecolorallocate($image, 255, 255, 255);
    $textColor = imagecolorallocate($image, random_int(0, 80), random_int(0, 80), random_int(0, 80));
    $lineColor = imagecolorallocate($image, random_int(100, 200), random_int(100, 200), random_int(100, 200));

    // 填背景
    imagefilledrectangle($image, 0, 0, $this->width, $this->height, $bg);

    // 畫雜訊線
    for ($i = 0; $i < 6; $i++) {
      imageline(
        $image,
        random_int(0, $this->width),
        random_int(0, $this->height),
        random_int(0, $this->width),
        random_int(0, $this->height),
        $lineColor
      );
    }

    // 若有 TTF 字體可用，可把字體放到 storage 或 resources/fonts 再用 imagettftext
    // 這裡優先用內建字型做簡單輸出，並隨機偏移每個字元
    $fontSize = intval($this->height * 1);

    // 若系統有 ttf 字體，可改成：
    // $fontPath = storage_path('fonts/YourFont.ttf');
    // imagettftext(...)

    $x = 10;
    $y = ($this->height / 2) + ($fontSize / 3);
    for ($i = 0; $i < strlen($text); $i++) {
      $angle = random_int(-20, 20);
      $char = $text[$i];

      // 使用內建字型寫入 single-char（使用 imagestring 會比較僵硬）
      // 改用 imagettftext 如果你加 ttf 字型
      imagestring($image, 5, $x, ($this->height / 2) - 8 + random_int(-4, 4), $char, $textColor);
      $x += $this->width / ($this->length) - 4;
    }

    // 輸出為 PNG 到緩衝區，base64 encode
    ob_start();
    imagepng($image);
    $imageData = ob_get_clean();
    imagedestroy($image);

    $base64 = 'data:image/png;base64,' . base64_encode($imageData);

    return $this->success('取得驗證碼', [
      'captcha_key' => $key,
      'image' => $base64,
      'ttl' => $this->ttl,
    ]);
  }

  public function verify(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'captcha_key' => 'required|string',
      'captcha' => 'required|string',
    ]);

    if ($validator->fails()) {
      // return response()->json(['message' => 'validation error', 'errors' => $validator->errors()], 422);
      return $this->error('驗證碼錯誤', $validator->errors(), 422);
    }

    $key = $request->input('captcha_key');
    $value = strtolower($request->input('captcha'));
    $cacheKey = 'captcha_' . $key;

    // if (!Cache::has($cacheKey)) {
    //   return response()->json(['message' => $key], 422);
    // }
    if (!Cache::has($cacheKey)) {
      // return response()->json(['message' => 'captcha expired or not found'], 422);
      return $this->error('驗證碼錯誤', ['captcha' => 'captcha expired or not found'], 422);
    }

    $expected = Cache::get($cacheKey);

    // 一次性驗證（驗證完刪除）
    Cache::forget($cacheKey);

    if ($expected !== $value) {
      return $this->error('驗證碼錯誤', ['captcha' => 'captcha mismatch'], 422);
    }

    return true;
  }
}
