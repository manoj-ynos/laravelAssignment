<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Upload;
use App\Http\Requests\{UserUpdateRequest,UserAddRequest};
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Mail;
use App;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		
        $this->authorize(User::class, 'index');
		
        if($request->ajax())
        {
            $users = new User;
			
            if($request->q)
            {
                $users = $users->where('name', 'like', '%'.$request->q.'%')->orWhere('email', $request->q);
            }
            $users = $users->where('id', '!=' ,1)->paginate(config('stisla.perpage'))->appends(['q' => $request->q]);
			
            return response()->json($users);
        }
        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	
    public function store(Request $request)
    {	
		
		$email = $request->email;
		$rem_token =  Str::random(32);
		
		$data = array('name'=>"Admin Demo",'rem_token'=>$rem_token);
		Mail::send("mail", $data,function ($message) use ($email,$rem_token) {			
			$message->to($email)->subject('Laravel HTML Testing Mail');
			$message->from('demoadmin@domain.com', 'Invitation from assignment');
		});
		  if (Mail::failures()) {
			// return failed mails
			return new Error(Mail::failures()); 
		}else{
			
			$user = User::create(['email' => $email,'u_status'=>0,"remember_token"=>$rem_token]);
			return response()->json("mail send ");
		}
      
    }
	
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
	public function profile_image(){
		$users = Auth::user();
		return view('admin/users/profile_image', compact('users'));
	}
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
		$fileName = time().'.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
       \Image::make($request->photo)->save(public_path('upload/users/').$fileName);
            $request->merge(['photo' => $fileName]);

           // $userPhoto = public_path('img/profile/').$currentPhoto;
		
		$store = array(          
            "user_image" => $fileName,
            //"status" => $request->post("speciality_status"),         
        );
		if($request->post("id") != ''){
			$User = new User();
		 $User->where(['id'=> $request->post("id")])->update($store);
		}
		 $data['status']="success";
		 return response()->json($data);		
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
	
    public function destroy(User $user)
    {	
		if($user->u_status==0){
			$user->u_status=1;
		}else{
			$user->u_status=0;
		}
		$user->save();
       
    }

    public function roles()
    {
        return response()->json(Role::get());
    }

    


}
