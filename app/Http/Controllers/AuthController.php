<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use App\Services\OtpService;
use App\Models\User;

class AuthController extends Controller
{

  protected OtpService $otpService;

  public function __construct(OtpService $otpService)
  {
    $this->otpService = $otpService;
  }

  public function register(Request $request)
  {
    $request->validate([
      'name' => 'required|string',
      'email' => 'required|email|unique:users',
      'phone' => 'required|digits:10|starts_with:09|unique:users,phone',
      'password' => 'required|string|min:6|confirmed',
    ]);

    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => bcrypt($request->password),
      'phone' => $request->phone,
    ]);

    // $this->otpService->sendOtpToEmail($request->email);

    return $this->success('註冊成功', $user, 201);
  }


  // AuthController.php
  public function login(Request $request)
  {
    $request->validate([
      'email' => 'required|email',
      'password' => 'required',
    ]);

    if (!Auth::attempt($request->only('email', 'password'))) {
      return $this->error('登入失敗', ['error' => '無效的帳號或密碼'], 401);
    }

    $user = Auth::user();

    // 判斷 Email 是否驗證
    if (is_null($user->email_verified_at)) {
      // $this->otpService->sendOtpToEmail($user->email);
      // 可以回傳錯誤，或要求跳轉驗證頁
      return $this->error('尚未驗證 Email', ['error' => '請先驗證 Email'], 403);
    }

    // if (is_null($user->phone_verified_at)) {
    //   return $this->error('尚未驗證手機號碼', ['error' => '請先驗證手機'], 403);
    // }

    $token = $user->createToken('api-token')->plainTextToken;
    return $this->success('登入成功', ['token' => $token]);
  }

  // 登出
  public function logout(Request $request)
  {
    $request->user()->currentAccessToken()->delete();
    return $this->success('已登出');
  }

  // 修改密碼
  public function changePassword(Request $request)
  {
    $request->validate([
      'current_password' => 'required',
      'new_password' => 'required|string|min:6|confirmed',
    ]);

    if (!Hash::check($request->current_password, $request->user()->password)) {
      return $this->error('密碼錯誤', [], 403);
    }

    $request->user()->update([
      'password' => Hash::make($request->new_password),
    ]);

    return $this->success('密碼已更新');
  }

  public function sendOtp(Request $request)
  {
    // 驗證格式
    $request->validate([
      'email' => 'required|email',
    ]);

    // 查詢使用者
    $user = User::where('email', $request->email)->first();

    if (!$user) {
      return response()->json(['message' => '此 Email 尚未註冊'], 422);
    }

    if ($user->email_verified_at !== null) {
      return response()->json(['message' => '此 Email 已驗證'], 422);
    }

    $stored = Cache::get("otp:$request->email");
    // 寄送 OTP
    $this->otpService->sendOtpToEmail($request->email);

    // return response()->json(['message' => 'OTP 已寄出']);
    return $this->success('OTP 已寄出', $stored);
  }

  public function verifyOtp(Request $request)
  {
    $request->validate([
      'email' => 'required|email',
      'otp' => 'required|digits:6',
    ]);

    try {
      $this->otpService->verifyOtp($request->email, $request->otp);
    } catch (\Exception $e) {
      if ($e->getMessage() === "OTP_INVALID") {
        return $this->error(
          '驗證錯誤，請輸入正確的驗證碼',
          [],
          422
        );
      }

      return response()->json([
        'message' => '伺服器錯誤',
      ], 500);
    }

    // 驗證成功 → 更新 email_verified_at
    $user = User::where('email', $request->email)->first();
    $user->email_verified_at = now();
    $user->save();

    $token = $user->createToken('api-token')->plainTextToken;
    return $this->success('驗證成功', ['token' => $token]);
  }

  public function verifyPassword(Request $request)
  {
    $request->validate(['password' => 'required|string']);
    $user = Auth::user();

    if (!Hash::check($request->password, $user->password)) {
      return $this->error('密碼錯誤', [], 401);
    }

    // 設置 flag，有效 5 分鐘
    Cache::put("user:{$user->id}:verified_for_name_change", true, now()->addMinutes(5));

    return $this->success('密碼驗證成功');
  }
}
