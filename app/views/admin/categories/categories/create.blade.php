@extends('layouts.admin')

@section('admincontent')
<p class='bg-primary flashmsg'>  <h2> {{ $categoryType->title }} </h2> </p>
    @if($errors->has())
    
    <ul>
      @foreach($errors->all() as $error)
        <li>{{$error}}</li>
      @endforeach
    </ul> 
    
    @endif

    {{ Form::open(array('route' => array('admin.categories.cat.save'), 'class' => 'form-horizontal')) }}
        @include('admin.categories.categories.form')
    {{ Form::close() }}
@stop
