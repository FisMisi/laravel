<h1>Events</h1>
<div class='has_user'>
Has User: <select id="hu" name="hu">
		<option value="2" @if (2 == $helperDataJson['hu']) selected="selected" @endif >All</option>
		<option value="1" @if (1 == $helperDataJson['hu']) selected="selected" @endif >Has User</option>
		<option value="0" @if (0 == $helperDataJson['hu']) selected="selected" @endif >Hasn't User</option>
	</select>
</div>

<div class="et_list">
Event Type: <select id="et" name="et">
		@foreach($helperDataJson['etList'] as $key => $value)
			<option value="{{$key}}" @if ($helperDataJson['et'] == $key) select="select" @endif >{{$value}}</option>
		@endforeach
	</select>
</div>
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
<div class="search">
<span id="searchbutton" name="searchbutton"><u>Search</u></span>
<script>
$("#searchbutton").click(function () {
	var ET = $("#et").val();
	var HU = $("#hu").val();
	var LIMIT = $("#limit").val();
	var PAGE = $("#page").val();
	var filtersArray = [];
	
	if (ET == 0 && HU == 2 && LIMIT == 20 && (PAGE == 1 || PAGE == undefined)) {
		window.location = "/administrator/events";
	} else {
		if (LIMIT != 20) {
			filtersArray[filtersArray.length] = "limit="+LIMIT;
		}
		
		if (ET != 2) {
			filtersArray[filtersArray.length] = "et="+ET;
		}
		
		if (HU != 2) {
			filtersArray[filtersArray.length] = "hu="+HU;
		}
		
		if (PAGE != 1 && PAGE != undefined) {
			filtersArray[filtersArray.length] = "page="+PAGE;
		}

		window.location = "/administrator/events?"+filtersArray.join("&");
	}
	
});
</script>
</div>

<table id="events">
	<colgroup>
		<col id="title"></col>
		<col id="entity_name"></col>
		<col id="entity_id"></col>
		<col id="session_id"></col>
		<col id="email"></col>
		<col id="created_at"></col>
	</colgroup>
	<tbody id="rows">
		@if (!is_null($helperDataJson['list']))
			<tr>
			<td>Event type</td>
			<td>Entity name</td>
			<td>Entity ID</td>
			<td>SESSION</td>
			<td>User email</td>
			<td>Time</td>
			</tr>
			@foreach($helperDataJson['list'] as $event)
				@if (isset($event))
					<tr>
						<td>{{$event->title}}</td>
						<td>{{$event->entity_name}}</td>
						<td>{{$event->entity_id}}</td>
						<td>{{$event->session_id}}</td>
						<td>{{$event->email}}</td>
						<td>{{$event->created_at}}</td>
					</tr>
				@endif
			@endforeach
		@endif
	</tbody>
</table>

