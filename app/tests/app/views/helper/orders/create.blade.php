<div class="wrapper group">
    <div class="row content-block-model">
            
    CREATE ORDER FROM {{ $helperDataJson['modell']->artist_name }}
    
   @if (Session::has('errors'))
   <div>
    <ul>
        @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>        
        @endforeach  
    </ul>
   </div>    
   @endif  
    
    
    {{ Form::open(array('route' => array('/order/save'))) }}   
    <div class="userdata">
        <table> 
            @foreach($helperDataJson['gsVideoCategories'] as $categ)
              <tr>
                <td>
                    {{ Form::radio('gs_video_category_id',$categ['categId'],(!is_null($helperDataJson['categId'])&& $categ['categId']==$helperDataJson['categId']? true : false)) }} 
                    {{ $categ['categTitle'] }}
                </td>
                <td>
                    price: {{ $categ['ex_vc_price'] }}$
                </td>
              </tr>  
            @endforeach
        </table>
    </div>
    
    <div class="userdata">
        {{ Form::label('from_name','Sender name')}}
        {{ Form::text('from_name',
                     (!is_null($helperDataJson['user']->nick) ? $helperDataJson['user']->nick 
                      : $helperDataJson['user']->first_name.' '.$helperDataJson['user']->last_name)) }}
    </div>
    
    <div class="userdata">
        {{ Form::label('from_email','Sender email')}}
        {{ Form::text('from_email',$helperDataJson['user']->email) }}
    </div>
    
    <div class="userdata">
        {{ Form::label('to_name','Addressee name')}}
        {{ Form::text('to_name') }}
    </div>
    
    <div class="userdata">
        {{ Form::label('to_email','Addressee email')}}
        {{ Form::text('to_email') }}
    </div>
    
    <div class="userdata">
        {{ Form::label('year','Sending Date')}} <br />
        {{ Form::label('year','Year')}} - {{ Form::text('year',null,['size'=>'4']) }}
        {{ Form::label('month','Month')}} - {{ Form::text('month',null,['size'=>'3']) }}
        {{ Form::label('day','Day')}}   - {{ Form::text('day',null,['size'=>'3']) }}
    </div>
    
    <div class="userdata">
        {{ Form::label('message1','Message to Modell')}} <br />
        {{ Form::textarea('message1') }}
    </div>
    
    <div class="userdata">
        {{ Form::label('message2','Message to Addressee')}} <br />
        {{ Form::textarea('message2') }}
    </div>
    
    <div class="userdata">
        {{ Form::label('payout_system_id','Choose how to get your money?')}} <br /> 
            @foreach(PayPutSystem::getActive() as $payput)
                {{ Form::radio('payout_system_id',$payput->pos_id) }}
                {{ Form::label('payout_system_id',$payput->pos_title) }} <br />
            @endforeach
    </div>
    <div class="userdata">
        {{ Form::hidden('model_id',$helperDataJson['modell']->id) }}
        {{ Form::hidden('user_id',$helperDataJson['user']->user_id) }}
        {{ Form::submit('save') }}
    </div>
    
    {{Form::close()}}
    </div>
</div>