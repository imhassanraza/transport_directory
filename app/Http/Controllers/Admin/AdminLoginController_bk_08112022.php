<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth, Session;
use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use Illuminate\Http\Request;

class AdminLoginController extends Controller
{
    protected $redirectTo = '/admin';
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    public function index()
    {
        return view('admin/login');
    }

    public function verify_login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::guard('admin')->attempt($credentials)){
            return redirect('admin');
        }else{
            return redirect("admin/login")->withErrors('Invalid email or password!.');
        }
    }

    public function logout() {
        Session::flush();
        Auth::logout();
        return redirect('admin/login');
    }
}
