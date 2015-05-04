<div style="color: white">   
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
    {{ Form::label('artist_name', 'Choose your Atist Name') }}
    {{ Form::text('artist_name') }}
  </div>
  <div class="userdata">
    {{ Form::label('fullname', 'Type your full name') }}
    {{ Form::text('fullname') }}
  </div>
  <div class="userdata">
    @if(isset($helperDataJson['userModel']->img_path))
        <div style="width: 200px; height:200px")>
        {{ HTML::image($helperDataJson['userModel']->img_path,$helperDataJson['userModel']->img_path) }}
        </div>
    @endif
    {{ Form::label('img_path', 'Upload your profile image') }}
    <div style="width:100px;">
        <img id="prew" src="#"  alt="" />
    </div>
    {{ Form::file('img_path') }}
  </div>
  <div class="userdata">
   <span class="input-label">Choose how to get your money?</span> 
    @foreach(PayPutSystem::getActive() as $payput)
     {{ Form::label('payout_system_id',$payput->pos_title) }}
     {{ Form::radio('payout_system_id',$payput->pos_id) }}
    @endforeach
  </div>
  <div class="userdata">
    {{ Form::label('country_id', 'Your country (visible for users)') }}  
    {{ Form::select('country_id', array('default' => 'Please choose') + Country::lists('country_name','country_id')) }}
  </div>
  <div class="userdata">
    {{ Form::label('city', 'Your city (invisible for users)') }}  
    {{ Form::text('city') }}
  </div>
  <div class="userdata">
    {{ Form::label('address', 'Your address (invisible for users)') }}  
    {{ Form::text('address') }}
  </div>
  @if($helperDataJson['userModel']->id == 0)
  <div class="userdata">
    {{ Form::checkbox('accept_tor') }} 
    {{ Form::label('accept_tor', 'I accept the Term of Registration') }}  
  </div>
 
  @endif
  {{ Form::submit('submit registration') }}
</div>

<script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#prew').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $("#img_path").change(function(){
        readURL(this);
    });
</script>