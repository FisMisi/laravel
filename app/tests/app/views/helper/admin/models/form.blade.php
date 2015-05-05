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
            {{ Form::label('artist_name', 'Atist Name',array('class' => 'col-sm-3 control-label')) }}
            <div class='col-sm-8'>
            {{ Form::text('artist_name', null, array('class' => 'form-control')) }}
            </div>
          </div>

          <div class="form-group">
            {{ Form::label('fullname', 'Full name',array('class' => 'col-sm-3 control-label')) }}
            <div class='col-sm-8'>
            {{ Form::text('fullname', null, array('class' => 'form-control')) }}
            </div>
          </div>

          <div class="form-group">
            @if(isset($helperDataJson['model']->img_path))
               <div class='col-sm-offset-3 col-sm-8'> 
                    <a href="{{$helperDataJson['model']->img_path}}" target="_blank">
                        {{ HTML::image($helperDataJson['model']->img_path,$helperDataJson['model']->img_path,array('style'=>'width:200px; height:auto')) }}
                    </a>
               </div> 
            @endif
          </div>

          <div class="form-group">
            {{ Form::label('payout_system_id', 'Get money',array('class' => 'col-sm-3 control-label')) }}  
            <div class="col-sm-7">
            @foreach(PayPutSystem::getActive() as $payput)
              <div class="radio">
                <label>  
                 {{ Form::radio('payout_system_id',$payput->pos_id) }} {{$payput->pos_name}}
                </label>
              </div>
            @endforeach
            </div>
          </div>
         
         <div class="form-group">
           {{ Form::label('categories', 'Categories',array('class' => 'col-sm-3 control-label')) }}  
            <div class="col-sm-7">
                <ul>
            @foreach(ModelCategoryType::getTypesCategoriesAdmin($helperDataJson['model']->id) as $categ)
                    <li> {{ $categ['type'] }} : {{ implode(', ',$categ['category']) }} </li>
            @endforeach
                </ul>
            </div>
         </div>
         
         <div class="form-group">
           {{ Form::label('languages', 'Speaking language(s)',array('class' => 'col-sm-3 control-label')) }}  
            <div class="col-sm-7">
                <ul>
            @foreach(GsLanguage::getLanguagesAdmin($helperDataJson['model']->id) as $lang)
                    <li> {{ $lang['name'] }} </li>
            @endforeach
                </ul>
            </div>
          </div>
         
          <div class="form-group">
           {{ Form::label('videos', 'Offered show categories',array('class' => 'col-sm-3 control-label')) }}  
            <div class="col-sm-7">
                <ul>
            @foreach(GsVideoCategory::getVideoCategoriesAdmin($helperDataJson['model']->id) as $categ)
                    <li> {{ $categ['title'] }} </li>
            @endforeach
                </ul>
            </div>
          </div>
          
          <div class="form-group">
            {{ Form::label('model_level_id', 'Level',array('class' => 'col-sm-3 control-label')) }}
           <div class='col-sm-8'> 
            {{ Form::select('model_level_id', array('0' => 'Automata') + GsModellLevel::lists('title','id'),null, array('class' => 'form-control')) }}
           </div> 
          </div>
         
          <div class="form-group">
            {{ Form::label('country_id', 'Country',array('class' => 'col-sm-3 control-label')) }}
           <div class='col-sm-8'> 
            {{ Form::select('country_id', array('default' => 'Please choose') + Country::lists('country_name','country_id'),null, array('class' => 'form-control')) }}
           </div> 
          </div>

          <div class="form-group">
            {{ Form::label('city', 'City',array('class' => 'col-sm-3 control-label')) }}  
            <div class='col-sm-8'>
            {{ Form::text('city', null, array('class' => 'form-control')) }}
            </div>
          </div>

          <div class="form-group">
            {{ Form::label('address', 'Address',array('class' => 'col-sm-3 control-label')) }}  
            <div class='col-sm-8'>
            {{ Form::text('address', null, array('class' => 'form-control')) }}
            </div>
          </div>

          <<div class="form-group">
            {{ Form::label('introducte','Introducte',array('class' => 'col-sm-3 control-label'))}}
            <div class='col-sm-8'>
            {{ Form::textarea('introducte', null, array('class' => 'form-control')) }}
            </div>
          </div>

          <div class="form-group">
            {{ Form::label('created_at', 'Created at',array('class' => 'col-sm-3 control-label')) }}  
            <div class='col-sm-8'>
            {{ $helperDataJson['model']->created_at }}
            </div>
          </div>

         <div class="form-group">
            {{ Form::label('updated_at', 'Updated at',array('class' => 'col-sm-3 control-label')) }}  
            <div class='col-sm-8'>
            {{ $helperDataJson['model']->updated_at }}
            </div>
          </div>

          <div class="form-group">
            <div class='col-sm-offset-3 col-sm-8'> 
            {{ Form::checkbox('accept_tor') }} 
            {{ Form::label('accept_tor', 'Accept the Term of Registration') }}  
            </div>
          </div>

          <div class="form-group">
            <div class='col-sm-offset-3 col-sm-8'> 
            {{ Form::checkbox('active') }} 
            {{ Form::label('active', 'Active') }}  
            </div>
          </div>

          <div class="form-group">
           <div class='col-sm-offset-3 col-sm-8'> 
            {{ Form::checkbox('validated') }} 
            {{ Form::label('validated', 'Validated') }}  
           </div> 
          </div>

          <div class='col-sm-offset-3 col-sm-8'> 
          {{ Form::submit('modify',array('class'=>'btn btn-success')) }}
          </div>
        </div>
    
       <!-- BAL OLDAL  -->
        <div class="col-sm-4">
            <div class="form-group"> 
               <h3>Personal documents</h3>
                @if(count($helperDataJson['model_documents']))
                 @foreach($helperDataJson['model_documents'] as $modelDocument)
                   <div class='col-sm-offset-3 col-sm-4'> 
                        <a href="{{$modelDocument->path}}" target="_blank">
                            {{ HTML::image($modelDocument->path,$modelDocument->path,array('style'=>'width:200px; height:auto')) }}
                        </a>
                   </div>
                 @endforeach
                @else
                <p><i>Még nem töltött fel dokumentumokat</i></p>
                @endif
              </div>
        </div>
</div>
