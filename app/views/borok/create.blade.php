@extends('layouts.main')

@section('content')
    @if($errors->has())
    <ul>
      @foreach($errors->all() as $error)
        <li>{{$error}}</li>
      @endforeach
    </ul> 
    @endif
    
    {{ Form::open(array('url' => 'menuitems/createItem', 'files'=>true, 'class' => 'form-horizontal')) }}
        @include('menuitems.form')
    {{ Form::close() }}
@stop
