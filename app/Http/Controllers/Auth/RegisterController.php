<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }
	public function check_user($rem_token){
		$User = User::where('remember_token',$rem_token)->first();
		//echo $User->email;die;
		if(!empty($User)){
			return view('auth.register',['email' => $User->email,'id'=>$User->id]);
		}else{
			echo "Link expire";die;
		}
		return redirect($redirect);
	}
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }
	
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
	
	 
    protected function create(array $data)
    {
		
		 //$User = new User();			
		 $store = array(
            "name" => $data['name'],
            'password' => Hash::make($data['password']),
			'u_status'=> 1
        );
		
		$user = User::where(['id'=> $data['id']])->update($store);       
        return redirect('/login');
    }
}
