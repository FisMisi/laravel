<div class="coll-sm-10" style="color: #151515">    
       @include('helper.admin.models.tabs')
            Active:
            <select id="active" name="active">
                    <option value="2" @if (2 == $helperDataJson['active']) selected="selected" @endif >All</option>
                    <option value="1" @if (1 == $helperDataJson['active']) selected="selected" @endif >Active</option>
                    <option value="0" @if (0 == $helperDataJson['active']) selected="selected" @endif >Inactive</option>
            </select>

            Validated:
            <select id="validated" name="validated">
                    <option value="2" @if (2 == $helperDataJson['validated']) selected="selected" @endif >All</option>
                    <option value="1" @if (1 == $helperDataJson['validated']) selected="selected" @endif >Validated</option>
                    <option value="0" @if (0 == $helperDataJson['validated']) selected="selected" @endif >Invalidated</option>
            </select>

            Country
            <select id='country' name='country'>
                    <option value="0" @if (0 == $helperDataJson['country']) selected="selected" @endif >All</option>
                    @foreach($helperDataJson['countryList'] as $country) 
                            <option value="{{$country['country_id']}}" @if ($country['country_id'] == $helperDataJson['country']) selected="selected" @endif >{{$country['country_name']}}</option>
                    @endforeach
            </select>

            Pay out system:
            <select id='payout' name='payout'>
                    <option value="0" @if (0 == $helperDataJson['payout']) selected="selected" @endif >All</option>
                    @foreach($helperDataJson['payoutList'] as $payout) 
                            <option value="{{$payout['pos_id']}}" @if ($payout['pos_id'] == $helperDataJson['payout']) selected="selected" @endif >{{$payout['pos_title']}}</option>
                    @endforeach
            </select>
            
            Level is manual:
            <select id="auto_level" name="auto_level">
                    <option value="2" @if (2 == $helperDataJson['autoLevel']) selected="selected" @endif >All</option>
                    <option value="1" @if (1 == $helperDataJson['autoLevel']) selected="selected" @endif >Yes</option>
                    <option value="0" @if (0 == $helperDataJson['autoLevel']) selected="selected" @endif >No</option>
            </select>
            
            Level:
            <select id='level' name='level'>
                    <option value="0" @if (0 == $helperDataJson['level']) selected="selected" @endif >All</option>
                    @foreach($helperDataJson['levelList'] as $level) 
                            <option value="{{$level['id']}}" @if ($level['id'] == $helperDataJson['level']) selected="selected" @endif >{{$level['title']}}</option>
                    @endforeach
            </select>

            Accept the Term of Registration:
            <select id="accept_tor" name="accept_tor">
                    <option value="2" @if (2 == $helperDataJson['accept_tor']) selected="selected" @endif >All</option>
                    <option value="1" @if (1 == $helperDataJson['accept_tor']) selected="selected" @endif >Accept</option>
                    <option value="0" @if (0 == $helperDataJson['accept_tor']) selected="selected" @endif >Not Accept</option>
            </select>

            Limit:
            <select id="limit" name="limit">
                    <option value="10" @if(10 == $helperDataJson['limit']) selected="selected" @endif >10</option>
                    <option value="20" @if(20 == $helperDataJson['limit']) selected="selected" @endif >20</option>
                    <option value="30" @if(30 == $helperDataJson['limit']) selected="selected" @endif >30</option>
                    <option value="40" @if(40 == $helperDataJson['limit']) selected="selected" @endif >40</option>
                    <option value="50" @if(50 == $helperDataJson['limit']) selected="selected" @endif >50</option>
            </select>

            @if($helperDataJson['needPager'])
            <div>ModelNum (to Select):{{$helperDataJson['modelsCount']}}</div> 
            Page:
            <select id="page" name="page">
                    @for($i=1;$i<=$helperDataJson['pagerOptions'];$i++)
                            <option value="{{$i}}" @if($helperDataJson['page'] == $i) selected="selected" @endif>{{$i}}</option>
                    @endfor
            </select>
            @endif
            <button type="button" id="searchbutton" name="searchbutton" class="btn btn-primary btn-sm">Search</button>
            <script>
            $("#searchbutton").click(function () {
            var ACTIVE = $("#active").val();
            var VALIDATED = $("#validated").val();
            var COUNTRY = $("#country").val();
            var PAYOUT = $("#payout").val();
            var LEVEL = $("#level").val();
            var AUTOLEVEL = $("#auto_level").val();
            var ACCEPT_TOR = $("#accept_tor").val();
            var LIMIT = $("#limit").val();
            var PAGE = $("#page").val();
            var filtersArray = [];

            if (ACTIVE == 2 && VALIDATED == 2 && COUNTRY == 0 && LEVEL == 0 && AUTOLEVEL == 2 && PAYOUT == 0 && ACCEPT_TOR == 2 && LIMIT == 20 && (PAGE == 1 || PAGE == undefined)) {
                    window.location = "/administrator/models";
            } else  {
                    if (ACTIVE != 2) {
                            filtersArray[filtersArray.length] = "active="+ACTIVE;
                    }

                    if (VALIDATED != 2) {
                            filtersArray[filtersArray.length] = "validated="+VALIDATED;
                    }

                    if (LIMIT != 20) {
                            filtersArray[filtersArray.length] = "limit="+LIMIT;
                    }

                    if (PAGE != 1 && PAGE != undefined) {
                            filtersArray[filtersArray.length] = "page="+PAGE;
                    }

                    if (COUNTRY != 0) {
                            filtersArray[filtersArray.length] = "country="+COUNTRY;
                    }

                    if (PAYOUT != 0) {
                            filtersArray[filtersArray.length] = "payout="+PAYOUT;
                    }

                    if (ACCEPT_TOR != 2) {
                            filtersArray[filtersArray.length] = "accept_tor="+ACCEPT_TOR;
                    }
                    
                    if (LEVEL != 0) {
                            filtersArray[filtersArray.length] = "level="+LEVEL;
                    }

                    if (AUTOLEVEL != 2) {
                            filtersArray[filtersArray.length] = "auto_level="+AUTOLEVEL;
                    }
                    window.location = "/administrator/models?"+filtersArray.join("&");
            } 
            });
            </script>
            
    </div>
  
    <table id="videos" class="table table-striped">

		@if (!is_null($helperDataJson['models']))
			<tr>
                            <th>Id</th>
                            <th>Full Name</th>
                            <th>Artist Name</th>
                            <th>Manual Level</th>
                            <th>Level</th>
                            <th>Accept the Term of Registration</th>
                            <th>Validated</th>
                            <th>Active</th>
                            <th>Country</th>
                            <th>Pay out system</th>
			</tr>
			@foreach($helperDataJson['models'] as $model)
			
				@if (isset($model))
					<tr>
                                                <td>{{$model['id']}}</td>
						<td>{{$model['fullname']}}</td>
						<td>{{$model['artist_name']}}</td>
                                                
                                                <td>@if ($model['is_manual']) Yes @else No @endif</td>
                                                <td>{{$model['title']}}</td>
                                               
						<td>@if ($model['accept_tor']) Yes @else No @endif</td>
						<td>@if ($model['validated'] == 0) No validated @else Yes @endif  </td>
						<td>@if ($model['active'] == 1) Active @else Inactive @endif  </td>
                                                <td>{{ $model['country_name'] }} </td>
                                                <td>{{ $model['pos_title'] }} </td>
						<td><a class="btn btn-info btn-sm" role="button" href={{route('/administrator/models/{id}', array('id' => $model['id']))}} >View</a></td>
                                                   
					</tr>
				@endif
			@endforeach
		@endif
    </table>

