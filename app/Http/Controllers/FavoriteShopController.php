<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop;

class FavoriteShopController extends Controller
{
    //

    public function toggle(Request $request, Shop $shop)
    {
        $user = Auth::user();

        if ($user->favoriteShops()->where('shop_id', $shop->id)->exists()) {
            $user->favoriteShops()->detach($shop->id);
            $favoriteShopIds = $user->favoriteShops()->pluck('shops.id');
            return $this->success('removed', $favoriteShopIds);
        } else {
            $user->favoriteShops()->attach($shop->id);
            $favoriteShopIds = $user->favoriteShops()->pluck('shops.id');
            return $this->success('added', $favoriteShopIds);
        }
    }

    public function list()
    {
        $user = Auth::user();
        $shops = $user->favoriteShops()->get();
        return $this->success('喜愛shop', $shops);
    }
}
