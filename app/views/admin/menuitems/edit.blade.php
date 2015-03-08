@extends('layouts.admin')

@section('admincontent')

    <p class='bg-primary flashmsg'> {{ $menuitem->name }} adatai </p>

    @if($errors->has())
    <ul>
      @foreach($errors->all() as $error)
        <li>{{$error}}</li>
      @endforeach
    </ul> 
    @endif
       
    {{ Form::model($menuitem,array('route' => array('admin.menuitems.update', $menuitem->id), 'method'=>'put', 'files'=>true, 'class' => 'form-horizontal')) }}
        @include('admin.menuitems.form')
    {{ Form::close() }}
    
@stop
