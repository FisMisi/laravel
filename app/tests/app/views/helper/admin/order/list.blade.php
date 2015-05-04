<div class="coll-sm-10" style="color: #151515">    
    
<h1>
    ORDERS
</h1>

        <div>
            ORDER COMPLATED
            <select id="storaged_video_id" name="storaged_video_id">
                      <option value="2" @if (2 == $helperDataJson['storaged_video_id']) selected="selected" @endif >All</option>
                      <option value="1" @if (1 == $helperDataJson['storaged_video_id']) selected="selected" @endif >Yes</option>
                      <option value="0" @if (0 == $helperDataJson['storaged_video_id']) selected="selected" @endif >No</option>
            </select>

            MODELL REJECTED
            <select id="is_rejected" name="is_rejected">
                      <option value="2" @if (2 == $helperDataJson['is_rejected']) selected="selected" @endif >All</option>
                      <option value="1" @if (1 == $helperDataJson['is_rejected']) selected="selected" @endif >Yes</option>
                      <option value="0" @if (0 == $helperDataJson['is_rejected']) selected="selected" @endif >No</option>
            </select>
            
            USER REJECTED
            <select id="is_said_back" name="is_said_back">
                      <option value="2" @if (2 == $helperDataJson['is_said_back']) selected="selected" @endif >All</option>
                      <option value="1" @if (1 == $helperDataJson['is_said_back']) selected="selected" @endif >Yes</option>
                      <option value="0" @if (0 == $helperDataJson['is_said_back']) selected="selected" @endif >No</option>
            </select>
             
            ADMIN REJECTED
            <select id="is_inactive" name="is_inactive">
                      <option value="2" @if (2 == $helperDataJson['is_inactive']) selected="selected" @endif >All</option>
                      <option value="1" @if (1 == $helperDataJson['is_inactive']) selected="selected" @endif >Yes</option>
                      <option value="0" @if (0 == $helperDataJson['is_inactive']) selected="selected" @endif >No</option>
            </select>
        </div>
        <div>
            DEADLINE STATUSZ
            <select id="send_date" name="send_date">
                    <option value="2" @if (2 == $helperDataJson['send_date']) selected="selected" @endif >All</option>
                    <option value="1" @if (1 == $helperDataJson['send_date']) selected="selected" @endif >Time's up</option>
                    <option value="0" @if (0 == $helperDataJson['send_date']) selected="selected" @endif >Live</option>
            </select>

            LIMIT
            <select id="limit" name="limit">
                      <option value="3" @if(3 == $helperDataJson['limit']) selected="selected" @endif >3</option>
                      <option value="10" @if(10 == $helperDataJson['limit']) selected="selected" @endif >10</option>
                      <option value="20" @if(20 == $helperDataJson['limit']) selected="selected" @endif >20</option>
                      <option value="30" @if(30 == $helperDataJson['limit']) selected="selected" @endif >30</option>
                      <option value="40" @if(40 == $helperDataJson['limit']) selected="selected" @endif >40</option>
            </select>
               
            ORDER BY:
                <select id="order" name="order">
                        <option value="0" @if (0 == $helperDataJson['order']) selected="selected" @endif >Order date</option>
                        <option value="1" @if (1 == $helperDataJson['order']) selected="selected" @endif >Send date</option>
                </select>
                <select id="ordered" name="ordered">
                        <option value="0" @if (0 == $helperDataJson['ordered']) selected="selected" @endif >Down</option>
                        <option value="1" @if (1 == $helperDataJson['ordered']) selected="selected" @endif >Up</option>
                </select>
              
            <button type="button" id="searchbutton" name="searchbutton" class="btn btn-primary btn-sm">Search</button>
            <a class="btn btn-primary btn-sm" @if (!count($helperDataJson['orders']))  disabled="disabled" 
               @endif   href={{ route('/administrator/orders/export',$_GET) }}>Export</a>
        </div>
    
    <script>
            $("#searchbutton").click(function () 
            {
                var ORDER_COMPLATED    = $("#storaged_video_id").val();
                var MODEL_SAID_BACK    = $("#is_rejected").val();
                var USER_SAID_BACK     = $("#is_said_back").val();
                var ADMIN_SAID_BACK    = $("#is_inactive").val();
                var SEND_DATE          = $("#send_date").val();
                
                var LIMIT = $("#limit").val();
                var PAGE = $("#page").val();
                var ORDER = $("#order").val();
                var ORDERED = $("#ordered").val();
                var filtersArray = [];

                if (ORDER_COMPLATED==2 && MODEL_SAID_BACK==2 && USER_SAID_BACK==2 && ADMIN_SAID_BACK==2 && SEND_DATE ==2 && LIMIT == 10 && ORDER == 0 && (PAGE == 1 || PAGE == undefined)) {
                        window.location = "/administrator/orders";
                } else {
                    
                       if (ORDER_COMPLATED != 2) {
                                filtersArray[filtersArray.length] = "storaged_video_id="+ORDER_COMPLATED;
                        }
                        
                        if (MODEL_SAID_BACK != 2) {
                                filtersArray[filtersArray.length] = "is_rejected="+MODEL_SAID_BACK;
                        }
                        
                        if (USER_SAID_BACK != 2) {
                                filtersArray[filtersArray.length] = "is_said_back="+USER_SAID_BACK;
                        }
                        
                        if (ADMIN_SAID_BACK != 2) {
                                filtersArray[filtersArray.length] = "is_inactive="+ADMIN_SAID_BACK;
                        }
                        
                        if (SEND_DATE != 2) {
                                filtersArray[filtersArray.length] = "send_date="+SEND_DATE;
                        }
                                          
                        if (LIMIT != 10) {
                                filtersArray[filtersArray.length] = "limit="+LIMIT;
                        }
                        
                        if (ORDER != 0) {
                                filtersArray[filtersArray.length] = "order="+ORDER;
                        }
                        
                        if (ORDERED != 0) {
                                filtersArray[filtersArray.length] = "ordered="+ORDERED;
                        }

                        window.location = "/administrator/orders?"+filtersArray.join("&");
                } 
            });
    </script>
    
</div>
<div class="table-responsive">
    @if (count($helperDataJson['orders']))
    <table id="orders" style="background-color: #8f785c;color: #404" class="table table-striped">
        <tr>
            <th>#Id</th>
            <th>Customer Email</th>
            <th>Modell Artist Name</th>
            <th>Video Category</th>
            <th>Rejected User</th>
            <th>Rejected Modell</th>
            <th>Rejected Admin</th>
            <th>Send Date</th>
            <th>Video Statusz</th>
            <th>Statusz</th>
            <th>Created</th>
            <th>Updated</th>
            <th>Actions</th>
        </tr>
        @foreach($helperDataJson['orders'] as $order)
            @if (isset($order))
                    <tr>
                            <td>{{$order->orderId}}</td>
                            <td>{{$order->userEmail}}</td>
                            <td>{{$order->artistName}}</td>
                            <td>{{$order->categoryTitle}}</td>
                            <td>@if($order->is_said_back == 1) Yes @else No @endif</td>
                            <td>@if($order->is_rejected == 1) Yes @else No @endif</td>
                            <td>@if($order->is_inactive == 1) Yes @else No @endif</td>
                            <td>{{ $order->sendDate }}</td>
                            <td>
                                @if(!is_null($order->videoId))
                                {{link_to_route('/administrator/video_storage/{id}', 
                                    'COMPLATED', 
                                    array('id' => $order->videoId),
                                    array('style'=>'color: blue')
                                    )
                                }}
                                @else
                                   <font color="red">NOT FINISHED</font>
                                @endif
                            </td>
                            <td>{{$order->orderStatusz}}</td>
                            <td>{{$order->created}}</td>
                            <td>{{$order->updated}}</td>

                            <td>{{-- Form::open(array('route' => array('/administrator/video_storaged_categories/update_statusz/{id}',$order['orderId']), 'role' => 'form')) --}}
                                <a class="btn btn-info btn-sm" role="button" href={{route('/administrator/orders/{id}', array('id' => $order->orderId))}} >View</a>

                                {{-- Form::close() --}}
                            </td>           
                    </tr>
            @endif
        @endforeach

    </table>
    @else
                <h3>Nincs találat</h3>
    @endif
    </div>
    {{ $helperDataJson['orders']->appends(array(
                                    'limit' =>Input::get('limit'),
                                    'storaged_video_id' => Input::get('storaged_video_id'),
                                    'is_rejected' => Input::get('is_rejected'),
                                    'is_said_back' => Input::get('is_said_back'),
                                    'is_inactive' => Input::get('is_inactive'),
                                    'send_date' => Input::get('send_date'),
                                    'order' => Input::get('order'),
                                    'ordered' => Input::get('ordered')
                                ))->links() 
    }}
   



