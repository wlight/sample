<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;

class SessionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    public function create ()
    {
        return view('sessions.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        if (Auth::attempt($credentials, $request->has('remember'))) {
            if (Auth::user()->activated){
                // 登录成功后的相关操作
                session()->flash('success', '欢迎回来！');
//            return redirect()->route('users.show', [Auth::user()]);
                return redirect()->intended(route('users.show', [Auth::user()]));
            }else{
                Auth::logout;
                session()->flash('warning', '您的账号未激活，请检查邮箱里的激活邮件进行激活。');
                return redirect('/');
            }
        }else {
            // 登录失败后的相关操作
            session()->flash('danger', '很抱歉，您的邮箱和密码不匹配！');
            return redirect()->back();
        }

        return;
    }

    public function destroy()
    {
        Auth::logout();
        session()->flash('success', '您已经成功退出!');
        return redirect('login');
    }
}
