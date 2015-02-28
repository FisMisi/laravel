@extends('layouts.main')

@section('content')
    @if($errors->has())
    <ul>
      @foreach($errors->all() as $error)
        <li>{{$error}}</li>
      @endforeach
    </ul> 
    @endif
    
    {{ Form::model($menuitem, array('url' => array('menuitems/update', $menuitem->id),'method'=>'put', 'class' => 'form-horizontal')) }}
        @include('menuitems.form')
    {{ Form::close() }}
@stop
