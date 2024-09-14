<?php

namespace App\Http\Controllers\Admin;
use App\Models\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index() {
         return view('admin.auth.login');

        // $admin['name']='ahmed';
        // $admin['email']='a@a.com';
        // $admin['username']='admin';
        // $admin['password']=bcrypt('123456789');
        // $admin['active']=1;
        // $admin['date']=date('Y-m-d');
        // $admin['com_code']=1;
        // $admin['added_by']=1;
        // $admin['updated_by']=1;

        //Admin::create($admin);
    }

   public function login(LoginRequest $request) {
        if(auth()->guard('admin')->attempt(['username'=>$request->input('username'),'password'=>$request->input('password')])){
            return redirect()->route('admin.dashboard');
        }else{
            return redirect()->route('admin.showlogin')->with(['error'=>'عفوا بيانات التسجيل غير صحيحة']);
        }
    }

    public function logout() {
        auth()->logout();
        return redirect()->route('admin.showlogin');

    }

    

}
