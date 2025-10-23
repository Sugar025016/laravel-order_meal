<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Shop;

class CategoryController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $categories = Category::all();
    return $this->success('取得分類列表成功', $categories);
  }
}
