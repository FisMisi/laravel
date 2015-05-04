<h1>Users</h1>
<div>
	<a href={{route('/administrator/felhasznalokezelo/{id}', array('id' => 0))}}>New</a>
</div>
<div name="type">
	@if ($helperDataJson['adminList'])
		<a href="/administrator/felhasznalokezelo"><span>Public Users</span></a>
		<span>Admin Users</span>
	@else
		<span>Public Users</span>
		<a href="/administrator/felhasznalokezelo/admin"><span>Admin Users</span></a>
@endif
</div>
<div name="filter">
	@if ($helperDataJson['adminList'])
	Admin type:
		<select id="sa" name="sa">
			<option value="2" @if (2 == $helperDataJson['sa']) selected="selected" @endif >All</option>
			<option value="0" @if (0 == $helperDataJson['sa']) selected="selected" @endif >NormalAdmin</option>
			<option value="1" @if (1 == $helperDataJson['sa']) selected="selected" @endif >SuperAdmin</option>
		</select>
	@else
	Confirmed:
		<select id="confirmed" name="confirmed">
			<option value="2" @if (2 == $helperDataJson['confirmed']) selected="selected" @endif >All</option>
			<option value="1" @if (1 == $helperDataJson['confirmed']) selected="selected" @endif >Confirmed</option>
			<option value="0" @if (0 == $helperDataJson['confirmed']) selected="selected" @endif >Not Confirmed</option>
		</select>
	@endif
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
@if ($helperDataJson['adminList']) 
$("#searchbutton").click(function () {
var SA = $("#sa").val();
var ACTIVE = $("#active").val();
var LIMIT = $("#limit").val();
var PAGE = $("#page").val();
var filtersArray = [];

if (SA == 2 && ACTIVE == 2 && LIMIT == 20 && (PAGE == 1 || PAGE == undefined)) {
	window.location = "/administrator/felhasznalokezelo/admin";
} else {
	if (SA != 2) {
		filtersArray[filtersArray.length] = "sa="+SA;
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
	window.location = "/administrator/felhasznalokezelo/admin?"+filtersArray.join("&");
}
});
@else
$("#searchbutton").click(function () {
var CONFIRMED = $("#confirmed").val();
var ACTIVE = $("#active").val();
var LIMIT = $("#limit").val();
var PAGE = $("#page").val();
var filtersArray = [];
if (CONFIRMED == 2 && ACTIVE == 2 && LIMIT == 20 && (PAGE == 1 || PAGE == undefined)) {
	window.location = "/administrator/felhasznalokezelo";
} else {
	if (CONFIRMED != 2) {
		filtersArray[filtersArray.length] = "confirmed="+CONFIRMED;
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
	window.location = "/administrator/felhasznalokezelo?"+filtersArray.join("&");
}
});
@endif
</script>
</div>

<div name="list">
	<table id="users">
		<colgroup>
			<col id="active"></col>
			@if ($helperDataJson['adminList'])
				
			@else
				<col id="confirmed"></col>
			@endif
			<col id="email"></col>
			<col id="nick"></col>
			<col id='id'></col>
		</colgroup>
		<tbody id="rows">
			@if (!is_null($helperDataJson['userList']))
				<tr>
					<td>Active</td>
					@if ($helperDataJson['adminList'])
					
					@else
						<td>Confirmed</td>
					@endif
					<td>Email</td>
					<td>Nick</td>
					<td>Modify</td>
				</tr>
				@foreach($helperDataJson['userList'] as $sc)
					@if (isset($sc))
						<tr>
							<td>{{$sc['active'] ? "active" : "inactive"}}</td>
							@if ($helperDataJson['adminList'])
								
							@else
								<td>{{$sc['confirmed']}}</td>
							@endif
							<td>{{$sc['email']}}</td>
							<td>{{$sc['nick']}}</td>
							@if ($helperDataJson['AUisSA'] || $helperDataJson['actualUser']->user_id == $sc['user_id']) 
								<td><a href={{route('/administrator/felhasznalokezelo/{id}', array('id' => $sc['user_id']))}}>Modify</a></td>
							@else
								<td> - </td>
							@endif
						</tr>
					@endif
				@endforeach
			@endif
			<tr>			
			</tr>
		</tbody>
	</table>

</div>