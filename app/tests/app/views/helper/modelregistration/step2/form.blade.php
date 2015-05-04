<div style="color: white">  
<div class="userdata">
 <h1>Hey,  {{ $helperDataJson['userModel']->artist_name }}</h1>
 <p>
     Your profile is almost ready. There are only 3 more steps to start rising
 </p>
 @if (Session::has('errors'))
   <div>
    <ul>
      @if ($errors->has())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>        
            @endforeach
        </div>
       @endif
    </ul>
   </div>    
   @endif  
 <div class="userdata">
     <h2>Your Profile</h2>
     <div style="width: 200px; height:140px")>
      {{ HTML::image($helperDataJson['userModel']->img_path, $helperDataJson['userModel']->fullname) }}
     </div> 
      {{ $helperDataJson['userModel']->fullname }}
      {{ $helperDataJson['userModel']->city }}
 </div>   
   
 {{-- PARTIAL-Show model categories --}}
        @include('helper.partials.modelcategories')  
   
 @if($helperDataJson['userModel']->id)  
    {{-- PARTIAL-Show model videos --}}
        @include('helper.partials.modelvideos', array('modelId' => $helperDataJson['userModel']->id))
 @endif
 
 <div class="userdata">
     <h2>Your Profile is 50% ready</h2>
     <progress value="50" max="100">
 </div>
 
  <div class="userdata">
    {{ Form::label('introducte','Please intorduce yourself')}}
    {{ Form::textarea('introducte') }}
  </div>
  
  <div class="userdata">
   <h2>Languages I speak</h2> 
    @foreach($helperDataJson['languages'] as $language)
        {{ Form::checkbox('gs_languages[]',$language['id'],!is_null($language['model_id'])) }} {{ $language['name'] }}
    @endforeach
  </div>
  
  <div class="userdata">
   <h2>Offered Show categories</h2> 
   <table>
       
        @foreach($helperDataJson['showCategories'] as $category)
          <tr>
            <td>
                {{ Form::checkbox('gs_video_categories[]',$category['id'],!is_null($category['model_id'])) }} 
                {{ $category['title'] }}
            </td>
<!--            <td>
                prefabricated price {{ Form::text('gs_vc_price',$helperDataJson['prices'][0][$category['id']]['referenced_price'],['size'=>'4']) }} 
                
            </td>-->
            <td>
                exclusive price    {{ Form::text('ex_vc_price__'.$category["id"],(!is_null($helperDataJson['modellVideoPrices'][$category['id']]['ex_vc_price'])
                                            ? $helperDataJson['modellVideoPrices'][$category['id']]['ex_vc_price'] 
                                            : $helperDataJson['prices'][1][$category['id']]['referenced_price']),['size'=>'4']) }} 
                (min: {{$helperDataJson['prices'][1][$category['id']]['min'] }},
                 max: {{$helperDataJson['prices'][1][$category['id']]['max'] }}) 
            </td>    
          </tr>
        @endforeach
       
   </table>
  </div>  
   
  <div class="userdata">
  <h2>Your Categories</h2>    
  @foreach($helperDataJson['categoryTypes'] as $type)
    <div>
        <b>{{ $type['title'] }}</b>
        @foreach(ModelCategory::getCategories($type['id'],$helperDataJson['userModel']->id) as $category)
            @if($type['multi'] == 1)
               {{ Form::checkbox($type['id'].'[]',$category['id'],!is_null($category['model_id'])) }} <i>{{ $category['title'] }}</i>  
            @else
               {{ Form::radio($type['id'],$category['id'],!is_null($category['model_id'])) }} <i>{{ $category['title'] }}</i>
            @endif 
        @endforeach
    </div>
    @endforeach 
  </div>
   
  <div class="userdata">
   <h2>Personal Documents</h2>
    {{ Form::file('documents[]',array('multiple'=>true)) }}
  </div>
  <div class="userdata">
   <h2>Introduction Video</h2>
    {{ Form::file('introduction_video') }}
  </div>
  <div class="userdata">
   <h2>Thank you video</h2>
   <p>
       It's important to say a big Thank you for everyone<br />
       who ordered a Gift Show from you. You can be.
   </p>
    {{ Form::file('thanks_video') }}
  </div>
  {{Form::hidden('model_id',$helperDataJson['userModel']->id)}}
  {{ Form::submit("I'm ready - Go to Admin Page") }}
</div>
</div>