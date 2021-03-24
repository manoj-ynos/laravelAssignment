<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\User;
Use Redirect;
use Illuminate\Support\Facades\Mail;



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
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, User $user)
    {
		$request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);


        $email = $request->get("email");
        $password = $request->get("password");
        $remember = !empty($request->get("remember")) && $request->get("remember") == "on" ? true : false;
		
		$chkEmail = User::where('email', $email)->first();
		if($chkEmail){
			if (password_verify($password, $chkEmail->password)) {
				 $user_data = Auth::attempt(['email' => $email, 'password' => $password,'u_status'=>1], $remember);
				
			}else{
				return \Redirect::back()->withErrors(["Password does not match"]);
			}
			
		}else{
			return \Redirect::back()->withErrors(["Invalid email or password"]);
		} 
		$is_valid_login = false;
        if ($user_data) {
            $is_valid_login = true;
        }
		if ($is_valid_login) {	
			$user_data = Auth::user();
			Auth::login($user_data);
			if($user_data->u_type==1){
				return redirect('admin/dashboard');
			}else{
				return redirect('admin/dashboard');
			}			
		}else{
			Auth::logout();
			return redirect(route('login'))->withInfo('Your Account have deactive. Please contact admin');
		}
    }
	
}
