@extends('layouts.main')

@section('content')
    @if($errors->has())
    <ul>
      @foreach($errors->all() as $error)
        <li>{{$error}}</li>
      @endforeach
    </ul> 
    @endif
    
    {{ Form::open(array('route' => array('categories.store'), 'class' => 'form-horizontal')) }}
        <div class="form-group">
            {{ Form::label('name', 'Kategória neve', array('class' => 'col-sm-3 control-label')) }}
            <div class='col-sm-4'>
                {{ Form::text('name', null, array('class' => 'form-control')) }}
            </div>
        </div>
        <div class="form-group">
            <div class='col-sm-offset-3 col-sm-4'>
                {{ Form::submit('mentés',array('class' => 'btn btn-success')) }}
            </div>
        </div>
    {{ Form::close() }}
@stop
