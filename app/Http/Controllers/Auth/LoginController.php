<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

     protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:agent')->except('logout');
        $this->middleware('guest:manager')->except('logout');
        $this->middleware('guest:admin')->except('logout');
    }

    // AGENTS

    public function showAgentLoginForm()
    {
        
        return view('auth.agent.login', ['url' => route('agent.login-view'), 'title' => 'Agent']);
    }

    public function agentLogin(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (Auth::guard('agent')->attempt($request->only(['email', 'password']), $request->get('remember'))) {
            return redirect()->intended('/agent/home');
        }

        return back()->withInput($request->only('email', 'remember'));
    }

    // MANAGERS

    public function showManagerLoginForm()
    {
        return view('auth.manager.login', ['url' => route('manager.login-view'), 'title'=>'Manager']);
    }
    
    public function managerLogin(Request $request)
    {
        
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6'
        ]);
          
        if (Auth::guard('manager')->attempt($request->only(['email','password']), $request->get('remember'))) {

            return redirect()->intended('/manager/home');
        }

        return back()->withInput($request->only('email', 'remember'));
    }

    // ADMINS

    public function showAdminLoginForm()
    {
       
        return view('pages.auth.admin.login', ['url' => route('admin.login-view'), 'title' => 'Admin']);
    }

    public function adminLogin(Request $request)
    {
        
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6'
        ]);
       
        if (Auth::guard('admin')->attempt($request->only(['email', 'password']), $request->get('remember'))) {
            return redirect()->intended('/admin/home');
        }

        return back()->withInput($request->only('email', 'remember'));
    }
}
