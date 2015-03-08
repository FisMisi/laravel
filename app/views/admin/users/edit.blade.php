@extends('layouts.main')

@section('content') 

    <h2>Adatok Frissítése</h2>
    
    {{ Form::model($user, array('url' => array('users/update', $user->id), 'files'=>true, 'method'=>'put','class'=>'form-horizontal')) }}
    
        @include('users.form')
    
    {{ Form::close() }}
@stop

