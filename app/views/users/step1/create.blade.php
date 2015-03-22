@extends('layouts.main')

@section('content') 

<h1 class="col-sm-offset-1"> Személyes adatok megadása </h1>
     
    {{ Form::open(array('route' => array('users.save'), 'files'=>true, 'class'=>'form-horizontal')) }}
    
        @include('users.step1.form')
    
    {{ Form::close() }}
@stop


