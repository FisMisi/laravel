<div class="row" style="color: white">
    <div class="col-sm-8">  
        @if (Session::has('errors'))
        <div class="form-group">
           <div class='col-sm-offset-2 col-sm-9'>
            <ul>
              @if ($errors->has())
                <div class="alert alert-success">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>        
                    @endforeach
                </div>
               @endif
            </ul>
           </div>
          </div>  
         @endif

          <div class="form-group">
            {{ Form::label('name', 'Name',array('class' => 'col-sm-3 control-label')) }}
            <div class='col-sm-8'>
            {{ Form::text('name', null, array('class' => 'form-control')) }}
            </div>
          </div>

          <div class="form-group">
            {{ Form::label('title', 'Title',array('class' => 'col-sm-3 control-label')) }}
            <div class='col-sm-8'>
            {{ Form::text('title', null, array('class' => 'form-control')) }}
            </div>
          </div>
         
          <div class="form-group">
            {{ Form::label('value', 'Value',array('class' => 'col-sm-3 control-label')) }}
            <div class='col-sm-3'>
            {{ Form::text('value', null, array('class' => 'form-control')) }}
            </div>
          </div>
        
          <div class="form-group">
            <div class='col-sm-offset-3 col-sm-4'>
                {{ Form::submit('Save',array('class' => 'btn btn-success')) }}
            </div>
        </div>
</div>
