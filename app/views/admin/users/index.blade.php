@extends('layouts.admin')

@section('admincontent')
    
    @section('szuro')
        <p class="bg-primary flashmsg">szűrő</p>
        <div class="szuro">
        
            {{ Form::open(array('route' => array('admin.users.postindex'),'class'=>'form-inline')) }}
                {{ Form::label('admin','Státusz') }}
                {{ Form::select('admin', array('all' => '- All -', 'user' => 'user',  'admin' => 'admin'),null, array('selected'=>'all', 'class' => 'form-control')) }}
                {{ Form::submit('GO',array('class'=>'btn btn-success')) }}
            {{ Form::close() }}
            <a class="btn btn-info" href="{{ route('/admin/users/export_users') }}">Export Users</a>
        </div>
    @stop
  <p class="bg-primary flashmsg">USERS</p>
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
        <td>{{ link_to_route('admin.users.edit', 'View',array($user->id),array('class' => 'btn btn-info btn-sm')) }}</td>
    </tr>    
    @endforeach
   </table>
  {{ $users->appends(array('admin' =>Input::get('admin')))->links(); }}
  
@stop
