
<div class="form-group">  
    {{ Form::label('first_name','Vezeték név', array('class' => 'col-sm-3 control-label')) }}
    <div class="col-sm-3">
        {{ Form::text('first_name',null, array('class' => 'form-control')) }}
        {{ $errors->first('first_name') }}
    </div>
</div>

<div class="form-group">  
    {{ Form::label('last_name','Kereszt név', array('class' => 'col-sm-3 control-label')) }}
    <div class="col-sm-3">
        {{ Form::text('last_name', null, array('class' => 'form-control')) }}
        {{ $errors->first('last_name') }}
    </div>
</div>

<div class="form-group">  
    {{ Form::label('username','Felhasználóinév', array('class' => 'col-sm-3 control-label')) }}
    <div class="col-sm-3">
        {{ Form::text('username', null, array('class' => 'form-control')) }}
        {{ $errors->first('username') }}
    </div>
</div>

<div class="form-group">  
    {{ Form::label('img_path','Profilkép', array('class' => 'col-sm-3 control-label')) }}
    <div class="col-sm-3">
        @if(isset($user->img_path))
            {{ HTML::image($user->img_path,$user->img_path,array('width' => '50')) }}
        @else <i>Még nem töltött fel képet</i>    
        @endif
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-3 col-sm-9">
      <div class="checkbox">
        <label>
          {{ Form::checkbox('admin') }} Admin
        </label>
      </div>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-3 col-sm-9">
      <div class="checkbox">
        <label>
          {{ Form::checkbox('delete') }} Delete {{ $user->getFullName() }}
        </label>
      </div>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-3 col-sm-9"> 
        {{ Form::submit('rögzít',array('class'=>'btn btn-success')) }}
    </div>
</div>