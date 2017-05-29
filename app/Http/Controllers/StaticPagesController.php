<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

class StaticPagesController extends Controller
{
    //
    public function home()
    {
        // 调用视图使用view()方法，里面传的是第一个参数：视图的路径和第二个参数：绑定的数据
        // ‘static_pages/home’的路径是resources/views/static_pages/home.blade.php

        $feed_items = [];
        if (Auth::check()){
            $feed_items = Auth::user()->feed()->paginate(30);
        }
        return view('static_pages/home', compact('feed_items'));
    }

    public function help()
    {
        return view('static_pages/help');
    }

    public function about()
    {
        return view('static_pages/about');
    }
}