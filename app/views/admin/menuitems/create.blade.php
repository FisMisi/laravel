@extends('layouts.admin')

@section('admincontent')

    <p class='bg-primary flashmsg'> Új Termék hozzáadása </p>
    
    @if($errors->has())
    <ul>
      @foreach($errors->all() as $error)
        <li>{{$error}}</li>
      @endforeach
    </ul> 
    @endif
    
    {{ Form::open(array('route' => array('admin.menuitems.create'), 'files'=>true, 'class' => 'form-horizontal')) }}
        @include('admin.menuitems.form')
    {{ Form::close() }}
@stop
