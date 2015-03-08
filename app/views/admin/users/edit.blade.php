@extends('layouts.admin')

@section('admincontent') 

    <p class='bg-primary flashmsg'> {{ $user->getFullName() }} adatai </p>
    
{{ Form::model($user, array('route' => array('admin.users.update', $user->id),'method'=>'put','class'=>'form-horizontal')) }}
    
        @include('admin.users.form')
    
{{ Form::close() }}
@stop

