<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;

class AddressController extends Controller
{
  public function index(Request $request)
  {
    $user = $request->user();
    return $this->success('取得地址列表成功', {$user->addrs,$user->});
  }

  public function store(Request $request)
  {

    $request->validate([
      'address_data_id' => 'required|integer|exists:address_data,id',
      'detail' => 'required|string|max:512',
      'lat' => 'nullable|numeric|between:-90,90',
      'lng' => 'nullable|numeric|between:-180,180',
    ]);

    $user = Address::create([
      'user_id' => $request->user()->id,
      'address_data_id' => $request->address_data_id,
      'detail' => $request->detail,
      'lat' => $request->lat,
      'lng' => $request->lng,
    ]);
    // $request->validate([
    //   'city' => 'required|string',
    //   'area' => 'required|string',
    //   'street' => 'required|string',
    //   'detail' => 'required|string',
    //   'lat' => 'numeric',
    //   'lng' => 'numeric',
    // ]);

    // $user = \App\Models\Address::create([
    //   'user_id' => $request->user()->id,
    //   'city' => $request->city,
    //   'area' => $request->area,
    //   'street' => $request->street,
    //   'detail' => $request->detail,
    //   'lat' => $request->lat,
    //   'lng' => $request->lng,
    // ]);

    return $this->success('新增地址成功', $user);
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
    $request->validate([
      'address_data_id' => 'sometimes|nullable|integer|exists:address_data,id',
      'detail' => 'sometimes|required|string|max:512',
      'lat' => 'nullable|numeric|between:-90,90',
      'lng' => 'nullable|numeric|between:-180,180',
    ]);
    // 驗證該地址是否屬於當前使用者
    $user = $request->user();
    // 檢查這個 address 是否屬於該 user
    $address = $user->addrs()->where('id', $id)->first();
    if (!$address) {
      return $this->error('Address 不存在', [], 422);
    }

    $address->update($request->only(['address_data_id', 'detail', 'lat', 'lng']));

    return $this->success('更新地址成功', $address);
  }
}
