<div class="row" style="color: #151515">
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
         
         <table class="table table-striped" style="color: #151515">
            <tr>
                <th>ID</th><td>{{$helperDataJson['order']->orderId}}</td>     
            </tr>
            <tr>
                <th>CUSTOMER EMAIL</th>
                <td>{{$helperDataJson['order']->userEmail}}</td>     
            </tr>
            <tr>
                <th>MODELL</th>
                <td>{{link_to_route('/administrator/models/{id}', 
                                            $helperDataJson['order']->artistName, 
                                            array('id' => $helperDataJson['order']->modelId),
                                            array('style'=>'color: blue')
                                            )
                                    }}
                </td>     
            </tr>
            <tr>
                <th>ORDERED VIDEO</th>
                <td>
                    @if(!is_null($helperDataJson['order']->videoId))
                        {{link_to_route('/administrator/video_storage/{id}', 
                                            'LINK', 
                                            array('id' => $helperDataJson['order']->videoId),
                                            array('style'=>'color: blue')
                                            )
                        }}
                    @else 
                        NOT FINISHED 
                    @endif
                </td>     
            </tr>
            <tr>
                <th>SEND DATE</th>
                <td>{{ $helperDataJson['order']->sendDate }}
                </td>     
            </tr>
            <tr>
                <th>MESSAGE TO GUEST</th>
                <td>
                    @if(!is_null($helperDataJson['order']->message1)){{ $helperDataJson['order']->message1 }} @else empty @endif
                </td>     
            </tr>
            <tr>
                <th>MESSAGE TO MODELL</th>
                <td>
                    @if(!is_null($helperDataJson['order']->message2)){{ $helperDataJson['order']->message2 }} @else empty @endif
                </td>     
            </tr>
            <tr>
                <th>SAID BACK BY USER</th>
                @if($helperDataJson['order']->is_said_back == 1)
                    <td>
                        Yes
                    </td>
                </tr>
                    <tr>
                        <th>SAID BACK TIME</th>
                        <td>
                            {{$helperDataJson['order']->backTime}}
                        </td>
                    </tr>
                @else
                    <td>
                        No 
                    </td>
                </tr>
                @endif
            <tr>
                <th>SAID BACK BY MODELL</th>
                @if($helperDataJson['order']->is_rejected == 1) 
                        <td>
                            Yes
                        </td>
                    </tr>
                    <tr>
                        <th>REJECTED TIME</th>
                        <td>
                            {{$helperDataJson['order']->rejectedTime}}
                        </td>
                    </tr>
                    <tr>
                        <th>REJECTED REASON</th>
                        <td>
                            {{$helperDataJson['order']->reason}}
                        </td>
                    </tr>
                @else 
                    <td>
                        No 
                    </td>
                    </tr>
                @endif
            <tr>
                <th>INACTIVE</th>
                <td>
                    {{Form::select('is_inactive', array(
                        '0' => 'No',
                        '1' => 'Yes',))
                    }}
                </td>
            </tr>
            <tr>
                <th>INACTIVE REASON</th>
                <td>
                   {{ Form::textarea('inactive_reason',null, ['size' => '30x3']) }}
                </td>
            </tr>
            <tr>
                <th>STATUSZ</th>
                <td>
                   {{$helperDataJson['order']->orderStatusz}}
                </td>
            </tr>
            <tr>
                <th>CUSTOM CUSTOMER NAME</th>
                <td>
                    @if(!is_null($helperDataJson['order']->fromName))
                        {{ $helperDataJson['order']->fromName }}
                    @else
                        - none -
                    @endif    
                </td>
            </tr>
            <tr>
                <th>CUSTOM CUSTOMER NAME</th>
                <td>
                    @if(!is_null($helperDataJson['order']->fromName))
                        {{ $helperDataJson['order']->fromName }}
                    @else
                        - none -
                    @endif    
                </td>
            </tr>
            <tr>
                <th>CUSTOM CUSTOMER EMAIL</th>
                <td>
                    @if(!is_null($helperDataJson['order']->fromEmail))
                        {{ $helperDataJson['order']->fromEmail }}
                    @else
                        - none -
                    @endif    
                </td>
            </tr>
            <tr>
                <th>GUEST NAME</th>
                <td>
                    @if(!is_null($helperDataJson['order']->toName))
                        {{ $helperDataJson['order']->toName }}
                    @else
                        - none -
                    @endif
                </td>
            </tr>
            <tr>
                <th>GUEST EMAIL</th>
                <td>
                    @if(!is_null($helperDataJson['order']->toEmail))
                        {{ $helperDataJson['order']->toEmail }}
                    @else
                        - none -
                    @endif
                </td>
            </tr>
            <tr>
                <th>CREATED</th>
                <td>
                     {{ $helperDataJson['order']->created }}
                </td>
            </tr>
            <tr>
                <th>UPDATED</th>
                <td>
                    @if(!is_null($helperDataJson['order']->updated))
                        {{ $helperDataJson['order']->updated }}
                    @else
                        - none -
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    {{ Form::submit('save',array('class'=>'btn btn-success btn-lg')) }}
                </td>
            </tr>
        </table> 
          
        
</div>
