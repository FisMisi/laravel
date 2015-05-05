<div>
<h1>Static Contents</h1>
<div>
<a href={{route('/administrator/staticcontent/{id}', array('id' => 0))}}>New</a>
</div>
<div class='lang'>
Language: <select id='lang' name='lang'>
@foreach($helperDataJson['langs'] as $key => $value) 
	<option value="{{$key}}"  @if ($key == $helperDataJson['lang']) selected="selected" @endif >  {{$value}}</option>	
@endforeach
</select>
</div>
<div class='active'>
Active: <select id="active" name="active">
		<option value="2" @if (2 == $helperDataJson['active']) selected="selected" @endif >All</option>
		<option value="1" @if (1 == $helperDataJson['active']) selected="selected" @endif >Active</option>
		<option value="0" @if (0 == $helperDataJson['active']) selected="selected" @endif >Inactive</option>
	</select>
</div>

<div class="search">
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
	var LANG = $("#lang").val();
	var ACTIVE = $("#active").val();
	var LIMIT = $("#limit").val();
	var PAGE = $("#page").val();
	var filtersArray = [];
	
	if (LANG == "no" && ACTIVE == 2 && LIMIT == 20 && (PAGE == 1 || PAGE == undefined)) {
		window.location = "/administrator/staticcontent";
	} else {
		if (LANG != "no") {
			filtersArray[filtersArray.length] = "lang="+LANG;
		}
		
		if (ACTIVE != 2) {
			filtersArray[filtersArray.length] = "active="+ACTIVE;
		}
		
		if (LIMIT != 20) {
			filtersArray[filtersArray.length] = "limit="+LIMIT;
		}
		
		if (PAGE != 1 && PAGE != undefined) {
			filtersArray[filtersArray.length] = "page="+PAGE;
		}

		window.location = "/administrator/staticcontent?"+filtersArray.join("&");
	}
});
</script>
</div>

	<table id="pager">
		<colgroup>
			<col id="active"></col>
			<col id="lang"></col>
			<col id="title"></col>
			<col id="class"></col>
			<col id='id'></col>
		</colgroup>
		<tbody id="rows">
			@if (!is_null($helperDataJson['list']))
				<tr>
					<td>Active</td>
					<td>Lang</td>
					<td>Title</td>
					<td>Class</td>
					<td>Modify</td>
				</tr>
				@foreach($helperDataJson['list'] as $sc)
					@if (isset($sc))
						<tr>
							<td>{{$sc['active'] ? "active" : "inactive"}}</td>
							<td>{{$sc['lang']}}</td>
							<td>{{$sc['title']}}</td>
							<td>{{$sc['class']}}</td>
							<td><a href={{route('/administrator/staticcontent/{id}', array('id' => $sc['id']))}}>Modify</a></td>
						</tr>
					@endif
				@endforeach
			@endif
			<tr>			
			</tr>
		</tbody>
	</table>
</div>