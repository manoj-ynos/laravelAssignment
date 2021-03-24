<aside id="sidebar-wrapper">
  <div class="sidebar-brand" style="background-image: url(../public/assets/img/avatar/avatar-1.png) !important;">
    <!--a href="{{ route('admin.dashboard') }}">{{ env('APP_NAME') }}</a-->
  </div>

  <ul class="sidebar-menu">
      <!--li class="menu-header">Dashboard</li-->
      <li class="{{ Request::route()->getName() == 'admin.dashboard' ? ' active' : '' }}"><a class="nav-link" href="{{ route('admin.dashboard') }}"><img src="{{ asset('public/assets/img/icon/dashboard.jpeg') }}" style="width: 15px;  margin-right: 10px;"> <span>Dashboard</span></a></li>
      @if(Auth::user()->can('manage-users'))
      <!--li class="menu-header">Users</li>
      <li class="{{ Request::route()->getName() == 'admin.users' ? ' active' : '' }}"><a class="nav-link" href="{{ route('admin.users') }}"><i class="fa fa-users"></i> <span>Users</span></a></li-->
	  <!--li class="menu-header">Specialities</li-->
      <li class="{{ Request::route()->getName() == 'admin.users' ? ' active' : '' }}"><a class="nav-link" href="{{ route('admin.users') }}"><img src="{{ asset('public/assets/img/icon/speciality.png') }}" style="width: 15px;  margin-right: 10px;"> <span>Users</span></a></li>
	  <!--li class="menu-header">Doctors</li-->
     @endif
	  @if(Auth::user()->u_type== 2)
		  <li class="{{ Request::route()->getName() == 'admin.profile_image' ? ' active' : '' }}"><a class="nav-link" href="{{ route('admin.profile_image') }}"><img src="{{ asset('public/assets/img/icon/speciality.png') }}" style="width: 15px;  margin-right: 10px;"> <span>Change Profile Image</span></a></li>
	 @endif
    </ul>
</aside>
