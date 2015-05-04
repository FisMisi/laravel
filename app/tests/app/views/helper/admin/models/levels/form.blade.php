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
            {{ Form::label('title', 'Title',array('class' => 'col-sm-5 control-label')) }}
            <div class='col-sm-6'>
            {{ Form::text('title', null, array('class' => 'form-control')) }}
            </div>
          </div>

          <div class="form-group">
            {{ Form::label('min_view', 'Minimum view number',array('class' => 'col-sm-5 control-label')) }}
            <div class='col-sm-2'>
            {{ Form::text('min_view', null, array('class' => 'form-control')) }}
            </div>
          </div>

          <div class="form-group">
            {{ Form::label('min_view_p_week', 'Minimum view number/week',array('class' => 'col-sm-5 control-label')) }}
            <div class='col-sm-2'>
            {{ Form::text('min_view_p_week', null, array('class' => 'form-control')) }}
            </div>
          </div>
         
           <div class="form-group">
            {{ Form::label('min_rating', 'Minimum rating',array('class' => 'col-sm-5 control-label')) }}
            <div class='col-sm-2'>
            {{ Form::text('min_rating', null, array('class' => 'form-control')) }}
            </div>
          </div>
         
          <div class="form-group">
            {{ Form::label('max_video_p_day', 'Max video/day',array('class' => 'col-sm-5 control-label')) }}
            <div class='col-sm-2'>
            {{ Form::text('max_video_p_day', null, array('class' => 'form-control')) }}
            </div>
          </div>
         
         <div class="form-group">
            {{ Form::label('pos', 'Position',array('class' => 'col-sm-5 control-label')) }}
            <div class='col-sm-2'>
            {{ Form::text('pos', null, array('class' => 'form-control')) }}
            </div>
          </div>

          <div class="form-group">
            <div class='col-sm-offset-3 col-sm-4'>
                {{ Form::submit('Save',array('class' => 'btn btn-success')) }}
            </div>
        </div>
</div>
