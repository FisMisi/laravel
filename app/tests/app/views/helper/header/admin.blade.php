<span>
LiveChannel Administrator Portal
</span>
<div> 
@if ($headerDatas['isAuth']) 
	<span>Logged In: <a href="/administrator/felhasznalokezelo/{{$headerDatas['user']->user_id}}" > {{$headerDatas['user']->email}} </a></span>
@endif

<span></span>
</div>
@if ($headerDatas['isAuth'])
<span class='login'>
	<a href='/adminlogout'> Logout </a>
	{{-- HTML::linkRoute('adminlogout', 'Logout') --}}
</span>
@endif