<div class="coll-sm-10" style="color: #151515">    
    <h1>
        <a href="{{ route('/administrator/video_storage') }}"> Videos </a> |
        <a href="{{ route('/administrator/video_storaged_categories') }}"> Video Categories </a>
    </h1>
    
        <div>
        
            Activated by user
            <select id="activated_user" name="activated_user">
                      <option value="2" @if (2 == $helperDataJson['activated_user']) selected="selected" @endif >All</option>
                      <option value="1" @if (1 == $helperDataJson['activated_user']) selected="selected" @endif >Yes</option>
                      <option value="0" @if (0 == $helperDataJson['activated_user']) selected="selected" @endif >No</option>
            </select>

            Activated by admin
            <select id="activated_admin" name="activated_admin">
                      <option value="2" @if (2 == $helperDataJson['activated_admin']) selected="selected" @endif >All</option>
                      <option value="1" @if (1 == $helperDataJson['activated_admin']) selected="selected" @endif >Yes</option>
                      <option value="0" @if (0 == $helperDataJson['activated_admin']) selected="selected" @endif >No</option>
            </select>

            Video time's up
            <select id="published_and_date" name="published_and_date">
                    <option value="2" @if (2 == $helperDataJson['published_and_date']) selected="selected" @endif >All</option>
                    <option value="1" @if (1 == $helperDataJson['published_and_date']) selected="selected" @endif >Yes</option>
                    <option value="0" @if (0 == $helperDataJson['published_and_date']) selected="selected" @endif >No</option>
            </select>

               Video Types 
            <select id='video_type' name='video_type'>
                    <option value="0" @if (0 == $helperDataJson['video_type']) selected="selected" @endif >All</option>
                    @foreach($helperDataJson['videoTypeList'] as $video) 
                            <option value="{{$video['id']}}" @if ($video['id'] == $helperDataJson['video_type']) selected="selected" @endif >{{$video['title']}}</option>
                    @endforeach
            </select>
         
        </div>
        <div>
            In Storage
            <select id="in_storage" name="in_storage">
                      <option value="2" @if (2 == $helperDataJson['in_storage']) selected="selected" @endif >All</option>
                      <option value="1" @if (1 == $helperDataJson['in_storage']) selected="selected" @endif >Yes</option>
                      <option value="0" @if (0 == $helperDataJson['in_storage']) selected="selected" @endif >No</option>
            </select>

            Has all the transformation
            <select id="over_trans_code" name="over_trans_code">
                      <option value="2" @if (2 == $helperDataJson['over_trans_code']) selected="selected" @endif >All</option>
                      <option value="1" @if (1 == $helperDataJson['over_trans_code']) selected="selected" @endif >Yes</option>
                      <option value="0" @if (0 == $helperDataJson['over_trans_code']) selected="selected" @endif >No</option>
            </select>

               Limit:
              <select id="limit" name="limit">
                      <option value="10" @if(10 == $helperDataJson['limit']) selected="selected" @endif >10</option>
                      <option value="20" @if(20 == $helperDataJson['limit']) selected="selected" @endif >20</option>
                      <option value="30" @if(30 == $helperDataJson['limit']) selected="selected" @endif >30</option>
                      <option value="40" @if(40 == $helperDataJson['limit']) selected="selected" @endif >40</option>
                      <option value="50" @if(50 == $helperDataJson['limit']) selected="selected" @endif >50</option>
              </select>
               
              Order by:
              <select id="order" name="order">
                      <option value="0" @if (0 == $helperDataJson['order']) selected="selected" @endif >Video time's up</option>
                      <option value="1" @if (1 == $helperDataJson['order']) selected="selected" @endif >Artist name</option>
              </select>
              <select id="ordered" name="ordered">
                      <option value="0" @if (0 == $helperDataJson['ordered']) selected="selected" @endif >Down</option>
                      <option value="1" @if (1 == $helperDataJson['ordered']) selected="selected" @endif >Up</option>
              </select> 
              <button type="button" id="searchbutton" name="searchbutton" class="btn btn-primary btn-sm">Search</button>
              <a class="btn btn-primary btn-sm" @if (!count($helperDataJson['videos']))  disabled="disabled" @endif   href={{ route('/administrator/video_storage/videodownload',$_GET) }}>Export Videos</a>
              
      
                  @if($helperDataJson['needPager'])
                          <div>CategoryNum (to Select):{{$helperDataJson['videosCount']}}</div> 
                          Page:
                          <select id="page" name="page">
                                  @for($i=1;$i<=$helperDataJson['pagerOptions'];$i++)
                                          <option value="{{$i}}" @if($helperDataJson['page'] == $i) selected="selected" @endif>{{$i}}</option>
                                  @endfor
                          </select>
                  @endif            
        </div>
    
    <script>
            $("#searchbutton").click(function () 
            {
                var ACTIVATED_USER     = $("#activated_user").val();
                var ACTIVATED_ADMIN    = $("#activated_admin").val();
                var PUBLISHED_AND_DATE = $("#published_and_date").val();
                var IN_STORAGE         = $("#in_storage").val();
                var OVER_TRANS_CODE    = $("#over_trans_code").val();
                
                var VIDEO_TYPE = $("#video_type").val();
                var LIMIT = $("#limit").val();
                var PAGE = $("#page").val();
                var ORDER = $("#order").val();
                var ORDERED = $("#ordered").val();
                var filtersArray = [];

                if (ACTIVATED_USER == 2 && ACTIVATED_ADMIN == 2 && PUBLISHED_AND_DATE == 2 && IN_STORAGE == 2 && OVER_TRANS_CODE==2 && VIDEO_TYPE==0 && LIMIT == 20 && ORDER == 0 && ORDERED == 0 && (PAGE == 1 || PAGE == undefined)) {
                        window.location = "/administrator/video_storage";
                } else {
                    
                      if (ACTIVATED_USER != 2) {
                                filtersArray[filtersArray.length] = "activated_user="+ACTIVATED_USER;
                        }
                        
                      if (ACTIVATED_ADMIN != 2) {
                                filtersArray[filtersArray.length] = "activated_admin="+ACTIVATED_ADMIN;
                        }
                        
                      if (PUBLISHED_AND_DATE != 2) {
                                filtersArray[filtersArray.length] = "published_and_date="+PUBLISHED_AND_DATE;
                        }
                        
                      if (IN_STORAGE != 2) {
                                filtersArray[filtersArray.length] = "in_storage="+IN_STORAGE;
                        }
                        
                       if (OVER_TRANS_CODE != 2) {
                                filtersArray[filtersArray.length] = "over_trans_code="+OVER_TRANS_CODE;
                        }    
                        
                     if (VIDEO_TYPE != 0) {
                                filtersArray[filtersArray.length] = "video_type="+VIDEO_TYPE;
                        }
                        
                    
                        if (LIMIT != 20) {
                                filtersArray[filtersArray.length] = "limit="+LIMIT;
                        }
                        
                        if (ORDER != 0) {
                                filtersArray[filtersArray.length] = "order="+ORDER;
                        }
                        
                        if (ORDERED != 0) {
                                filtersArray[filtersArray.length] = "ordered="+ORDERED;
                        }

                        if (PAGE != 1 && PAGE != undefined) {
                                filtersArray[filtersArray.length] = "page="+PAGE;
                        }

                        window.location = "/administrator/video_storage?"+filtersArray.join("&");
                } 
            });
    </script>
            
    </div>
 
<table style="color: #404" id="videos" class="table table-striped">
		@if (count($helperDataJson['videos']))
			<tr>
                            <th>Id</th>
                            <th>Model Artist Name</th>
                            <th>Activated by user</th>
                            <th>Activated by admin</th>
                            <th>Availbility in storage</th>
                            <th>Has all the transformation</th>
                            <th>Video time's up</th>
                            <th>Type</th>
                            <th>Rating</th>
                            <th>Actions</th>
			</tr>
			@foreach($helperDataJson['videos'] as $video)
			
				@if (isset($video))
					<tr>
						<td>{{$video['id']}}</td>
						<td>{{$video['artist_name']}}</td>
						<td>@if ($video['active_user']) Yes @else No @endif</td>
                                                <td>@if ($video['active_admin']) Yes @else No @endif</td>
                                                <td>@if ($video['in_storage']) Yes @else No @endif</td>
                                                <td>@if ($video['over_trans_code']) Yes @else No @endif</td>
                                                <td>{{ $video['published_and_date'] }}</td>
                                                <td>{{ $video['videoTypeTitle'] }} </td>
                                                <td>{{ $video['rating'] }} </td>
						<td><a class="btn btn-info btn-sm" role="button" href={{route('/administrator/video_storage/{id}', array('id' => $video['id']))}} >View</a></td>                                                 
					</tr>
				@endif
			@endforeach
		@else
                <h1 style="color: white"><i>Video not found in database</i></h1>
                @endif
    </table>

