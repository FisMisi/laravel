@extends('layouts.main')

@section('content')
<div class="col-sm-offset-2">    
    {{ Form::open(array('route' => 'login.post', 'class'=>'form-horizontal')) }}
        <div class="form-group"> 
            <h1 class="col-sm-offset-3">Belépés</h1>
            
            {{ Form::label('username','Felhasználói név', array('class' => 'col-sm-3 control-label')) }}
            <div class="col-sm-3">
                {{ Form::text('username', null, array('class' => 'form-control')) }}
                {{ $errors->first('username','<p class="text-warning">:message</p>') }}
            </div>
        </div>
    
        <div class="form-group">  
            {{ Form::label('password','Jelszó', array('class' => 'col-sm-3 control-label')) }}
            <div class="col-sm-3">
                {{ Form::password('password', array('class' => 'form-control')) }}
                {{ $errors->first('password','<p class="text-warning">:message</p>') }}
            </div>
        </div>
        
        <div class="form-group">
            <div class="col-sm-offset-3">
                <div class="checkbox">
                    {{ Form::checkbox('remember')}}{{ Form::label('remember', 'Remember me')}}
                </div>
            </div>
        </div>
            
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9"> 
                {{ Form::submit('belépés',array('class'=>'btn btn-success')) }}
            </div>
        </div>    
    {{ Form::close() }}
</div>    
@stop
