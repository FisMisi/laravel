@extends('layouts.main')

@section('content') 

    <h2>Személyes adatok frissítése</h2>
    
    {{ Form::model($user, array('url' => array('users/update', $user->id), 'files'=>true, 'method'=>'put','class'=>'form-horizontal')) }}
    
        @include('users.step1.form')
    
    {{ Form::close() }}
@stop

