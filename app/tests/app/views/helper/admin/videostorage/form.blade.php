<div class="row" style="color: white">
    <div class="col-sm-10"> 
        <!-- Hibaüzenetek megjelenítése -->
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
            {{ Form::label('artist_name', 'Model Artist Name:',array('class' => 'col-sm-5 control-label')) }}
            <div class='col-sm-7'>
            {{ Form::label('artist_name', $helperDataJson['videos']->artist_name,array('class' => 'col-sm-7 control-label')) }}
            </div>
        </div>
        
        <div class="form-group">
            {{ Form::label('videoTitle', 'Video title:',array('class' => 'col-sm-5 control-label')) }}
            <div class='col-sm-7'>
            {{ Form::text('videoTitle',$helperDataJson['videos']->videoTitle,array('class' => 'col-sm-7 control-label','style'=>'color:black')) }}
            </div>
        </div>  
         
        <div class="form-group">
            {{ Form::label('videoName', 'Video name:',array('class' => 'col-sm-5 control-label')) }}
            <div class='col-sm-7'>
            {{ Form::label('videoName', (($helperDataJson['videos'][0]->videoName) ? $helperDataJson['videos']->videoName : '- empty -'),array('class' => 'col-sm-7 control-label')) }}
            </div>
        </div> 
         
        <div class="form-group">
            {{ Form::label('active_user', 'Activated by user:',array('class' => 'col-sm-5 control-label')) }}
            <div class='col-sm-7'>
            {{ Form::label('active_user',(($helperDataJson['videos'][0]->active_user) ? 'Yes' : 'No') ,array('class' => 'col-sm-7 control-label')) }}
            </div>
        </div>
         
        <div class="form-group">
           <div class='col-sm-offset-6 col-sm-6'> 
            {{ Form::checkbox('active_admin') }} 
            {{ Form::label('active_admin', 'Activated by admin') }}  
           </div> 
        </div> 
        
         <div class="form-group">
            {{ Form::label('inactivated_desctription', 'Inactivated description:',array('class' => 'col-sm-5 control-label')) }}
            <div class='col-sm-7'>
            {{ Form::textarea('inactivated_desctription',$helperDataJson['videos'][0]->inactivated_desctription,array('class' => 'col-sm-7 control-label','style'=>'color:black','size' => '30x3')) }}
            </div>
        </div> 
           
        <div class="form-group">
            {{ Form::label('in_storage', 'Availbility in storage:',array('class' => 'col-sm-5 control-label')) }}
            <div class='col-sm-7'>
            {{ Form::label('in_storage',($helperDataJson['videos'][0]->in_storage) ? Yes : No ,array('class' => 'col-sm-7 control-label')) }}
            </div>
        </div>  
         
        <div class="form-group">
            {{ Form::label('published_and_date', 'Published And Date:',array('class' => 'col-sm-5 control-label')) }}
            <div class='col-sm-7'>
            {{ Form::label('published_and_date', $helperDataJson['videos'][0]->published_and_date,array('class' => 'col-sm-7 control-label')) }}
            </div>
        </div>
         
        <div class="form-group">
            {{ Form::label('over_trans_code', 'Over Trans Code:',array('class' => 'col-sm-5 control-label')) }}
            <div class='col-sm-7'>
            {{ Form::label('over_trans_code', (($helperDataJson['videos'][0]->over_trans_code) ? $helperDataJson['videos']->over_trans_code : '- empty -'),array('class' => 'col-sm-7 control-label')) }}
            </div>
        </div>  
         
        <div class="form-group">
            {{ Form::label('local_store_path', 'Local Store Path:',array('class' => 'col-sm-5 control-label')) }}
            <div class='col-sm-7'>
            {{ Form::label('local_store_path', (($helperDataJson['videos'][0]->local_store_path) ? $helperDataJson['videos']->local_store_path : '- empty -'),array('class' => 'col-sm-7 control-label')) }}
            </div>
        </div>   
         
        <div class="form-group">
            {{ Form::label('storage_reference', 'Storage Reference:',array('class' => 'col-sm-5 control-label')) }}
            <div class='col-sm-7'>
            {{ Form::label('storage_reference', (($helperDataJson['videos'][0]->storage_reference) ? $helperDataJson['videos']->storage_reference : '- empty -'),array('class' => 'col-sm-7 control-label')) }}
            </div>
        </div>
         
        <div class="form-group">
            {{ Form::label('rating_number', 'Total Ratings:',array('class' => 'col-sm-5 control-label')) }}
            <div class='col-sm-7'>
            {{ Form::label('rating_number', $helperDataJson['videos'][0]->rating_number,array('class' => 'col-sm-7 control-label')) }}
            </div>
        </div>     
        
        <div class="form-group">
            {{ Form::label('rating', 'Rating:',array('class' => 'col-sm-5 control-label')) }}
            <div class='col-sm-7'>
            {{ Form::label('rating', $helperDataJson['videos'][0]->rating,array('class' => 'col-sm-7 control-label')) }}
            </div>
        </div>   
        
        <div class="form-group">
            {{ Form::label('type_id', 'Video Type:',array('class' => 'col-sm-5 control-label')) }}
            <div class='col-sm-5'>
            {{ Form::select('type_id',StoragedVideoType::lists('title','id'),null, array('class' => 'col-sm-5 form-control')) }}
            </div>
        </div>
         
        
        <div class="form-group">
            {{ Form::label('created_at', 'Created:',array('class' => 'col-sm-5 control-label')) }}
            <div class='col-sm-7'>
            {{ Form::label('created_at', $helperDataJson['videos'][0]->created_at,array('class' => 'col-sm-8 control-label')) }}
            </div>
        </div> 
         
        <div class="form-group">
            {{ Form::label('updated_at', 'Updated:',array('class' => 'col-sm-5 control-label')) }}
            <div class='col-sm-7'>
            {{ Form::label('updated_at', $helperDataJson['videos'][0]->updated_at,array('class' => 'col-sm-8 control-label')) }}
            </div>
        </div> 
          
        <div class='col-sm-offset-6 col-sm-6'> 
          {{ Form::submit('modify',array('class'=>'btn btn-success')) }}
          </div>
        </div>
    

</div>
