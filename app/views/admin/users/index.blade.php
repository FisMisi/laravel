@extends('layouts.admin')

@section('admincontent')

        <p class="bg-primary">szűrő</p>
        <div class="szuro">
            
           
        {{ Form::open(array('route' => array('admin.users.index'),'class'=>'form-horizontal')) }}
            {{ Form::label('adminbb','Státusz') }}
            {{ Form::text('admin') }}
<!--            {{ Form::select('admin', array( 'def' => '- All -', '0' => 'user', '1' => 'admin'),null, array('selected'=>'def', 'class' => 'form-control')) }}-->
            {{ Form::submit('GO') }}
        {{ Form::close() }}
        </div>
   
  <p class="bg-primary">USERS</p>
   <table class="table table-striped">
    <tr>
        <th>Full Name</th>
        <th>User Name</th>
        <th>Admin</th>
    </tr>
    @foreach($users as $user)
    <tr>
        <td>{{ $user->getFullName() }}</td>
        <td>{{ $user->username }}</td>
        <td>@if($user->admin == 1) Yes @else No @endif</td>
    </tr>    
    @endforeach
   </table>
  {{ $users->links(); }}
  
@stop
