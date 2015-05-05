<h1>Site Management</h1>
Limit:
<select id="limit" name="limit">
	<option value="10" @if(10 == $helperDataJson['limit']) selected="selected" @endif >10</option>
	<option value="20" @if(20 == $helperDataJson['limit']) selected="selected" @endif >20</option>
	<option value="30" @if(30 == $helperDataJson['limit']) selected="selected" @endif >30</option>
	<option value="40" @if(40 == $helperDataJson['limit']) selected="selected" @endif >40</option>
	<option value="50" @if(50 == $helperDataJson['limit']) selected="selected" @endif >50</option>
</select>
@if($helperDataJson['needPager'])
Page:
<select id="page" name="page">
	@for($i=1;$i<=$helperDataJson['pagerOptions'];$i++)
		<option value="{{$i}}" @if($helperDataJson['page'] == $i) selected="selected" @endif>{{$i}}</option>
	@endfor
</select>
@endif
<span id="searchbutton" name="searchbutton"><u>Search</u></span>
<script>
$("#searchbutton").click(function () {
var LIMIT = $("#limit").val();
var PAGE = $("#page").val();
var filtersArray = [];

if (LIMIT == 20 && (PAGE == 1 || PAGE == undefined)) {
	window.location = "/administrator/oldalkezelo";
} else  {
	if (LIMIT != 20) {
		filtersArray[filtersArray.length] = "limit="+LIMIT;
	}
	
	if (PAGE != 1 && PAGE != undefined) {
		filtersArray[filtersArray.length] = "page="+PAGE;
	}

	window.location = "/administrator/oldalkezelo?"+filtersArray.join("&");
} 
});
</script>
<div>
<a href={{route('/administrator/oldalkezelo/{id}', array('id' => 0))}}>New</a>
</div>
<div>
	<table id="pager">
		<colgroup>
			<col id="active"></col>
			<col id="name"></col>
			<col id="route"></col>
			<col id="layout"></col>
			<col id="na"></col>
			<col id="no"></col>
			<col id="id"></col>
		</colgroup>
		<tbody id="rows">
			@if (!is_null($helperDataJson['routings']))
				<tr>
					<td>Active</td>
					<td>Name</td>
					<td>Routing</td>
					<td>Layout</td>
					<td>Need Auth</td>
					<td>Need Over18</td>
					<td>Modify</td>
				</tr>
				@foreach($helperDataJson['routings'] as $route)
					@if (isset($route))
						<tr>
							<td>{{$route->active ? "active" : "inactive"}}</td>
							<td>{{$route->routing_name}}</td>
							<td>{{$route->routing_path}}</td>
							<td>{{$route->layout_name}}</td>
							<td>{{$route->need_auth ? "Yes" : "No"}}</td>
							<td>{{$route->needover18 ? "Yes" : "No"}}</td>
							<td><a href={{route('/administrator/oldalkezelo/{id}', array('id' => $route->id))}}>Modify</a></td>
						</tr>
					@endif
				@endforeach
			@endif
			<tr>			
			</tr>
		</tbody>
	</table>
</div>