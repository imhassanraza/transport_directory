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
            'phone_no' => 'required',
            'password' => 'required',
        ]);
        $credentials = $request->only('phone_no', 'password');
        if (Auth::guard('admin')->attempt($credentials)){
            $isactive = Auth::guard('admin')->user()->status;
            if($isactive == 1) {
                add_login_logs(Auth::guard('admin')->user()->id);
                return redirect('admin');
            } else {
                Auth::logout();
                Session::flush();
                return redirect("admin/login")->withErrors('You are temporarily blocked. Please contact to admin!.');
            }
            return redirect('admin');
        }else{
            return redirect("admin/login")->withErrors('Invalid phone number or password!.');
        }
    }
    public function logout() {
        update_login_logs(Auth::guard('admin')->user()->id);
        Session::flush();
        Auth::logout();
        return redirect('admin/login');
    }
}