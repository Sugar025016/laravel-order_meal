
<x-mail::message>
{{-- 標題 --}}
# 您的 OTP 驗證碼 🔐

{{-- 主要訊息 --}}
您好，  
您正在進行操作驗證，請使用以下 OTP 驗證碼：

<div style="font-size: 28px; font-weight: bold; color: #1D72B8; margin: 20px 0; text-align: center;">
    {{ $otp }}
</div>

此驗證碼將在 **5 分鐘內** 過期，請盡快使用。

{{-- 按鈕 --}}
<x-mail::button :url="'http://localhost:5173'" color="primary">
前往網站
</x-mail::button>

感謝您使用 {{ config('app.name') }}，祝您有美好的一天！  

<br>
{{ config('app.name') }} 團隊
</x-mail::message>