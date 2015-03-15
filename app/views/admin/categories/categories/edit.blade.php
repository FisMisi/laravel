@extends('layouts.admin')

@section('admincontent')
    @if($errors->has())
    <ul>
      @foreach($errors->all() as $error)
        <li>{{$error}}</li>
      @endforeach
    </ul> 
    @endif
    
    <h2>Modify {{ $categoryType->title }} </h2>
    
    {{ Form::model($category, array('route' => array('admin.categories.cat.update', $category->id), 'method' => 'put', 'class' => 'form-horizontal')) }}
        @include('admin.categories.categories.form') 
    {{ Form::close() }}
@stop
