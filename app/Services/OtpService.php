<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Mail\OtpMail;

class OtpService
{
    public function sendOtpToEmail(string $email)
    {
        $otp = rand(100000, 999999);

        Cache::put("otp:$email", $otp, 300);
        Log::info("Sending OTP to email: {$email}, OTP: {$otp}");
        Mail::to($email)->send(new OtpMail($otp));
    }

    public function verifyOtp(string $email, string $otp)
    {
        $stored = Cache::get("otp:$email");
        Log::info("Stored OTP = " . ($stored ?? 'null'));
        // echo $stored;
        if (!$stored || (string)$stored !== (string)$otp) {
            throw new \Exception("OTP_INVALID");
        }

        Cache::forget("otp:$email");
        return true;
    }
}
