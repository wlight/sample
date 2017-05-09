<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth',[
            'only' => ['edit', 'update', 'destroy']
        ]);

        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    public function index (){
        $users = User::paginate(30);
//        dd($users);
        return view('users.index', compact('users'));
    }

    public function create ()
    {
        return view('users.create');
    }
    public function show ($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    public function store (Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

//        Auth::login($user);
//        session()->flash('success', '欢迎，您将开始一段新的旅程！');
//        return redirect()->route('users.show', [$user]);
//        注册成功，发送激活邮件
        $this->sendEmailConfirmtionTo($user);
        session()->flash('success', '激活邮件已经发到您的邮箱，请注意查收。');
        return redirect('/');
    }

    public function edit ($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    public function update ($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'confirmed|min:6'
        ]);
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        $data = [];
        $data['name'] = $request->name;
        if ($request->password){
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);
        session()->flash('success', '个人资料更新成功');
        return redirect()->route('users.show', $id);
    }

    public function destroy ($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '成功删除用户！');
        return back();
    }

    public function sendEmailConfirmtionTo($user)
    {
        $view = 'emails.confirm';
    // compact() 函数创建一个由参数所带变量组成的数组。如果参数中存在数组，该数组中变量的值也会被获取，
    // 本函数返回的数组是一个关联数组，键名为函数的参数，键值为参数中变量的值。
        $data = compact('user');
        $from = 'wormside@126.com';
        $name = 'wormside';
        $to = $user->email;
        $subject = '感谢您注册 Sample 应用，请确认您的邮箱。';

        Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject){
            $message->from($from, $name)->to($to)->subject($subject);
        });
    }

    public function confirmEmail($token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();

        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success', '恭喜你，激活成功！');
        return redirect()->route('users.show', [$user]);
    }
}
