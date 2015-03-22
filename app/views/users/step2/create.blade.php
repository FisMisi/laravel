@extends('layouts.main')

@section('content') 

<h2 class="col-sm-offset-1">Kedves {{ $user->getFullName() }}, kérem adja meg a kínálatának megfelelő adatait</h2>
    
    {{ Form::open(array('route' => array('users.register'),'class'=>'form-horizontal')) }}
    
        @include('users.step2.form')
    
    {{ Form::close() }}
@stop


