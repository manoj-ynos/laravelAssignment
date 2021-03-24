@extends('layouts.admin-master')

@section('title')
Profile Image
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Profile Image</h1>	
  </div>
  <div class="section-body">
	<profileimage-component users='{!! $users->toJson() !!}'></profileimage-component>
   </div>
</section>
@endsection
