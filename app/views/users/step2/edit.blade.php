@extends('layouts.main')

@section('content') 

    <h2>További adatok frissítése Frissítése</h2>
    
    {{ Form::model($user, array('url' => array('/users/update2'),'method'=>'put','class'=>'form-horizontal')) }}
    
        @include('users.step2.form')
    
    {{ Form::close() }}
@stop

