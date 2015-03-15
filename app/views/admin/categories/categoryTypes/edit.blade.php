@extends('layouts.admin')

@section('admincontent')
    @if($errors->has())
    <ul>
      @foreach($errors->all() as $error)
        <li>{{$error}}</li>
      @endforeach
    </ul> 
    @endif
    
    {{ Form::model($categoryType, array('route' => array('admin.categories.categoryType.update', $categoryType->id), 'method' => 'put', 'class' => 'form-horizontal')) }}
        @include('admin.categories.categoryTypes.form') 
    {{ Form::close() }}
@stop
