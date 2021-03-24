<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Speciality;
use App\Conversation;
use App\ConversationDetail;

class DashboardController extends Controller
{
	protected $route_name;
    protected $module_singular_name;
    protected $module_plural_name;

    public function __construct() {
        $this->route_name = 'dashboard';
        $this->module_singular_name = 'Dashboard';
        //$this->module_plural_name = 'Admins';
    }

    public function index()
    {
		//$specialities = Speciality::where(['status'=> 1])->count();  
		$users = User::where(['id'=> !1])->count(); 		
        return view('admin.dashboard.index')->with(array('users'=>$users));
    }
	
}
