@extends('layouts.admin')

@section('admincontent')
<p class='bg-primary flashmsg'> Új Típus hozzáadása </p>
    @if($errors->has())
    
    <ul>
      @foreach($errors->all() as $error)
        <li>{{$error}}</li>
      @endforeach
    </ul> 
    
    @endif
    
    {{ Form::open(array('route' => array('admin.categories.type.save'), 'class' => 'form-horizontal')) }}
         @include('admin.categories.categoryTypes.form') 
    {{ Form::close() }}
@stop
