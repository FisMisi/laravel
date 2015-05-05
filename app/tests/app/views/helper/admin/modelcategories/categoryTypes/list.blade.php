<div class="coll-sm-10" style="color: #151515">    
<h2>Model Categories</h2>  
<div class="row"> 
    <div class="col-sm-12">
        <a class="btn btn-primary btn-sm" href="{{ route('/administrator/model_categories/type/{id}', array('id' => 0)) }}"> New Category Type</a>
        <a class="btn btn-link" href="{{ route('/administrator/model_categories') }}"> All category Type</a>
        @if(count($helperDataJson['categoryTypes']))
            @foreach($helperDataJson['categoryTypes'] as $category)
            <a class="btn btn-link" href="{{ route('/administrator/model_categories/{id}',array('id'=>$category->id)) }}"> {{ $category->title }} </a>
            @endforeach
        @endif
    </div>    
</div>    
    <div>
        Active:
          <select id="active" name="active">
                  <option value="2" @if (2 == $helperDataJson['active']) selected="selected" @endif >All</option>
                  <option value="1" @if (1 == $helperDataJson['active']) selected="selected" @endif >Active</option>
                  <option value="0" @if (0 == $helperDataJson['active']) selected="selected" @endif >Inactive</option>
          </select>

           Limit:
          <select id="limit" name="limit">
                  <option value="10" @if(10 == $helperDataJson['limit']) selected="selected" @endif >10</option>
                  <option value="20" @if(20 == $helperDataJson['limit']) selected="selected" @endif >20</option>
                  <option value="30" @if(30 == $helperDataJson['limit']) selected="selected" @endif >30</option>
                  <option value="40" @if(40 == $helperDataJson['limit']) selected="selected" @endif >40</option>
                  <option value="50" @if(50 == $helperDataJson['limit']) selected="selected" @endif >50</option>
          </select>
          <button type="button" id="searchbutton" name="searchbutton" class="btn btn-primary btn-sm">Search</button>
          <!--    <span id="searchbutton" name="searchbutton"><u>Search</u></span> -->

              @if($helperDataJson['needPager'])
                      <div>CategoryNum (to Select):{{$helperDataJson['categoriesCount']}}</div> 
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
                var ACTIVE = $("#active").val();
                var LIMIT = $("#limit").val();
                var PAGE = $("#page").val();
                var filtersArray = [];

                if (ACTIVE == 2 && LIMIT == 20 && (PAGE == 1 || PAGE == undefined)) {
                        window.location = "/administrator/model_categories";
                } else  {
                        if (ACTIVE != 2) {
                                filtersArray[filtersArray.length] = "active="+ACTIVE;
                        }

                        if (LIMIT != 20) {
                                filtersArray[filtersArray.length] = "limit="+LIMIT;
                        }

                        if (PAGE != 1 && PAGE != undefined) {
                                filtersArray[filtersArray.length] = "page="+PAGE;
                        }

                        window.location = "/administrator/model_categories?"+filtersArray.join("&");
                } 
            });
    </script>

    <table id="catgeories" class="table table-striped">

		@if (!is_null($helperDataJson['categoriesList']))
			<tr>
                            <th>Id</th>
                            <th>Title</th>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Statusz</th>
                            <th>Actions</th>
			</tr>
			@foreach($helperDataJson['categoriesList'] as $category)
			
				@if (isset($category))
					<tr>
                                                <td>{{$category['id']}}</td>
						<td>{{$category['title']}}</td>
                                                <td>{{$category['name']}}</td>
                                                <td>@if($category['pos']=="") <i>Not Specified</i> @else {{ $category['pos'] }} @endif </td>
                                                <td>@if($category['active']== 1 ) Active @else Inactive @endif </td>
						<td>{{ Form::open(array('route' => array('/administrator/model_categories/type/update_statusz/{id}',$category['id']), 'role' => 'form')) }}
                                                    <a class="btn btn-info btn-sm" role="button" href={{route('/administrator/model_categories/type/{id}', array('id' => $category['id']))}} >View</a>
                                                    <button type="submit" name="/administrator/model_categories/type/update_statusz/{id}" class="btn btn-success btn-sm">
                                                        @if($category['active']== 1 ) Inactivated @else Activated @endif
                                                    </button>
                                                    {{ Form::close() }}
                                                </td>
					</tr>
				@endif
			@endforeach
		@endif
<!--	</tbody>-->
    </table>

</div>