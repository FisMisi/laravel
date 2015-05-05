<div class="row" style="color: white">
    <div class="col-sm-8">  
        @if (Session::has('errors'))
        <div class="form-group">
           <div class='col-sm-12'>
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
            {{ Form::label('title', 'Video Category Title',array('class' => 'col-sm-5 control-label')) }}
            <div class='col-sm-6'>
            {{ Form::text('title', null, array('class' => 'form-control')) }}
            </div>
          </div>

          <div class="form-group">
            {{ Form::label('name', 'Name',array('class' => 'col-sm-5 control-label')) }}
            <div class='col-sm-6'>
            {{ Form::text('name', null, array('class' => 'form-control')) }}
            </div>
          </div>
              
         <div class="form-group">
            <div class='col-sm-offset-5 col-sm-4'> 
            {{ Form::checkbox('active') }} 
            {{ Form::label('active', 'Active') }}  
            </div>
          </div>
         
         <fieldset>
             <legend>PREFABRICATED VIDEO PRICES</legend>
              
                @foreach($helperDataJson['modellLevels'] as $level)
                  <div class="form-group">
                    {{ Form::label('title',$level['title'],array('class' => 'col-sm-offset-2 col-sm-3 control-label')) }}
                    <div class='col-sm-4'>
                    Minimum{{ Form::text('min__0__'.$level["id"],$helperDataJson['prices'][0][$level['id']]['min'], array('class' => 'form-control')) }}
                    Maximum{{ Form::text('max__0__'.$level["id"],$helperDataJson['prices'][0][$level['id']]['max'], array('class' => 'form-control')) }}
                    Referenced{{ Form::text('referenced_price__0__'.$level["id"],$helperDataJson['prices'][0][$level['id']]['referenced_price'], array('class' => 'form-control')) }}
                    </div>
                  </div>  
                @endforeach

         </fieldset>
         
         <fieldset>
             <legend>EXCLUSIVE VIDEO PRICES</legend>
             
                 @foreach($helperDataJson['modellLevels'] as $level)
                  <div class="form-group">
                    {{ Form::label('title',$level['title'],array('class' => 'col-sm-offset-2 col-sm-3 control-label')) }}
                    <div class='col-sm-4'>
                    Minimum{{ Form::text('min__1__'.$level["id"],$helperDataJson['prices'][1][$level['id']]['min'], array('class' => 'form-control')) }}
                    Maximum{{ Form::text('max__1__'.$level["id"],$helperDataJson['prices'][1][$level['id']]['max'], array('class' => 'form-control')) }}
                    Referenced{{ Form::text('referenced_price__1__'.$level["id"],$helperDataJson['prices'][1][$level['id']]['referenced_price'], array('class' => 'form-control')) }}
                    </div>
                  </div>  
                @endforeach
             
         </fieldset> 
         
         
        <div class="form-group">
            <div class='col-sm-offset-5 col-sm-4'>
                {{ Form::submit('Save',array('class' => 'btn btn-success btn-lg')) }}
            </div>
        </div>
</div>
