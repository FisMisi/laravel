@extends('layouts.main')

@section('content') 

    <h2>Új alkalmazott regisztrálása</h2>
    {{ Form::open(array('route' => array('users.register'),'class'=>'form-horizontal')) }}
    
        @include('users.form')
    
    {{ Form::close() }}
@stop


