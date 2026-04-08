<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Address;
use App\Services\GeocodingService;

class AddressController extends Controller
{
  public function index(Request $request)
  {
    Log::error('add addresses request', [
      'request' => $request->all(),
    ]);
    $user = $request->user();
    $addresses = $user->addrs;

    Log::error('add addresses addresses', [
      'addresses' => $addresses->all(),
    ]);

    return $this->success('取得地址列表成功', [
      'addresses' => $addresses,
      'currentAddress' =>  $user->currentAddress            // 地址列表
    ]);
    //     return response()->json([
    //     'test' => 'ok',
    // ]);
  }

  public function store(Request $request, GeocodingService $geo)
  {

    $request->validate([
      'city' => 'required|string',
      'area' => 'required|string',
      'street' => 'required|string',
      'detail' => 'required|string',
    ]);

    // 組完整地址
    $fullAddress = $request->city . $request->area . $request->street . $request->detail;

    // 取得座標
    $coords = $geo->getCoordinates($fullAddress);

    if (!$coords) {
      return $this->error('無法從 Google 取得座標', [], 422);
    }
    $addresss = Address::create([
      'user_id' => $request->user()->id,
      'city' => $request->city,
      'area' => $request->area,
      'street' => $request->street,
      'detail' => $request->detail,
      'lat' => $coords['lat'] ?? null,   // 新增欄位
      'lng' => $coords['lng'] ?? null,   // 新增欄位
    ]);

    return $this->success('新增地址成功', $addresss);
  }


  public function destroy(Request $request, $id)
  {
    // 驗證該地址是否屬於當前使用者
    $user = $request->user();

    // 檢查這個 address 是否屬於該 user
    $address = $user->addrs()->where('id', $id)->first();
    if (!$address) {
      return $this->error('Address 不存在', [], 422);
    }

    // 更新使用者的 current_address_id
    $user->addrs()->where('id', $id)->delete();
    $user->save();

    return $this->success('刪除地址成功');
  }

  public function update(Request $request, $id)
  {
    // 驗證請求資料
    $validated = $request->validate([
      'city' => 'required|string',
      'area' => 'required|string',
      'street' => 'required|string',
      'detail' => 'required|string',
    ]);
    // 驗證該地址是否屬於當前使用者
    $user = $request->user();
    // 檢查這個 address 是否屬於該 user
    $address = $user->addrs()->where('id', $id)->first();

    if (!$address) {
      return $this->error('Address 不存在', [], 422);
    }

    // 更新地址
    $address->update($validated);

    return $this->success('更新地址成功', $address);
  }



  public function setCurrent(Request $request, $id)
  {
    $user = $request->user();

    // 確認地址屬於該使用者
    $address = $user->addrs()->findOrFail($id);

    // 更新使用者的 current_address_id
    $user->current_address_id = $address->id;
    $user->save();

    return $this->success('設定外送地址成功', ['currentAddressId' => $user->current_address_id]);
  }
}
