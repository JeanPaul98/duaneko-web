<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;

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
    }

    public function showLoginForm()
    {
        return view('pages.auth.login');
    }

    /**
     * Bloque la connexion tant que le compte (manager/agent) n'est pas validé.
     */
    protected function authenticated(Request $request, $user)
    {
        if (!$user->isValidated()) {
            $this->guard()->logout();

            return redirect()->route('login')
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => $this->accountStatusMessage($user->status)]);
        }
    }

    private function accountStatusMessage(string $status): string
    {
        if ($status === 'rejected') {
            return "Votre compte professionnel a été rejeté. Contactez votre administrateur.";
        }

        return "Votre compte professionnel est en attente de validation.";
    }
}
