<h1>Tags</h1>
<div name="type">
	@if ($helperDataJson['isgroups'])
		<a href="/administrator/tag"><span>External Tags</span></a>
		<a href="/administrator/tag/internal"><span>Internal Tags</span></a>
		<span>Groups</span>
	@else
		@if ($helperDataJson['internalList'])
			<a href="/administrator/tag"><span>External Tags</span></a>
			<span>Internal Tags</span>
			<a href="/administrator/tag/group"><span>Groups</span></a>
		@else
			<span>External Tags</span>
			<a href="/administrator/tag/internal"><span>Internal Tags</span></a>
			<a href="/administrator/tag/group"><span>Groups</span></a>
		@endif
	@endif
</div>
<div>
	<a href="/administrator/regentag"><span>NE Nyomd Meg!!</span></a>
</div>
@if ($helperDataJson['isgroups'])
<a href="/administrator/tag/mod/gr__0"><span>New</span></a>
<table id="groups">
	<colgroup>
		<col id="Group Name"></col>
		<col id="Pos"></col>
		<col id="Active"></col>
	</colgroup>
	<tbody id="rows">
		@if (!is_null($helperDataJson['groupList']))
			<tr>
			<td>Group Name</td>
			<td>Pos</td>
			<td>Active</td>
			<td>Modify</td>
			</tr>
			@foreach($helperDataJson['groupList'] as $group)
				@if (isset($group))
					<tr>
						<td>{{$group['tag_group_name']}}</td>
						<td>{{$group['pos']}}</td>
						<td>@if($group['active'] == 1) Active @else Inactive @endif</td>
						<td><a href={{route('/administrator/tag/mod/{id}', array('id' => "gr__".$group['tag_group_id']))}}>Click</a></td>
					</tr>
				@endif
			@endforeach
		@endif
	
	</tbody>
</table>






@else
@if ($helperDataJson['internalList'])
<div class="newinternaltag">
	<a href="/administrator/tag/mod/int__0"><span>Új létrehozása</span></a>
</div>
Active:
<select id="active" name="active">
	<option value="3" @if (3 == $helperDataJson['active']) selected="selected" @endif >Mind</option>
	<option value="2" @if (2 == $helperDataJson['active']) selected="selected" @endif >Tilt</option>
	<option value="1" @if (1 == $helperDataJson['active']) selected="selected" @endif >Megjelenik</option>
	<option value="0" @if (0 == $helperDataJson['active']) selected="selected" @endif >Nem jelenik meg</option>
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
$("#searchbutton").click(function () {
var ACTIVE = $("#active").val();
var LIMIT = $("#limit").val();
var PAGE = $("#page").val();
var filtersArray = [];
if (ACTIVE == 3 && LIMIT == 20 && (PAGE == 1 || PAGE == undefined)) {
	window.location = "/administrator/tag/internal";
} else  {
	if (ACTIVE != 3) {
		filtersArray[filtersArray.length] = "active="+ACTIVE;
	}
	
	if (LIMIT != 20) {
		filtersArray[filtersArray.length] = "limit="+LIMIT;
	}
	
	if (PAGE != 1 && PAGE != undefined) {
		filtersArray[filtersArray.length] = "page="+PAGE;
	}
	window.location = "/administrator/tag/internal?"+filtersArray.join("&");
} 
});
</script>

<table id="internals">
	<colgroup>
		<col id="Tag Name"></col>
		<col id="Tag SEO Name"></col>
		<col id="Viewed"></col>
		<col id="Pos"></col>
		<col id="Active"></col>
		<col id="Modify"></col>
	</colgroup>
	<tbody id="rows">
		@if (!is_null($helperDataJson['tagList']))
			<tr>
			<td>Tag Name</td>
			<td>Tag SEO Name</td>
			<td>Viewed</td>
			<td>Pos</td>
			<td>Active</td>
			<td>Modify</td>
			</tr>
			@foreach($helperDataJson['tagList'] as $tag)
				@if (isset($tag))
					<tr>
						<td>{{$tag['internal_tag_name']}}</td>
						<td>{{$tag['internal_tag_seo_name']}}</td>
						<td>{{$tag['see_count']}}</td>
						<td>{{$tag['pos']}}</td>
						<td>@if($tag['active'] == 2) Tilt @elseif($tag['active'] == 1) Megjelenik @else Nem jelenik meg @endif</td>
						<td><a href={{route('/administrator/tag/mod/{id}', array('id' => "int__".$tag['internal_tag_id']))}}>Click</a></td>
					</tr>
				@endif
			@endforeach
		@endif
	</tbody>
</table>




@else
Has Internal:
<select id="hi" name="hi">
	<option value="2" @if (2 == $helperDataJson['hi']) selected="selected" @endif >Mind</option>
	<option value="1" @if (1 == $helperDataJson['hi']) selected="selected" @endif >Van belső</option>
	<option value="0" @if (0 == $helperDataJson['hi']) selected="selected" @endif >Nincs belső</option>
</select>
Partner:
<select id="partner" name="partner">
	<option value="0" @if (0 == $helperDataJson['partner']) selected="selected" @endif >Mind</option>
	@foreach($helperDataJson['partnerList'] as $partner)
		<option value="{{$partner['partner_id']}}" @if ($partner['partner_id'] == $helperDataJson['partner']) selected="selected" @endif >{{$partner['partner_name']}}</option>
	@endforeach
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
$("#searchbutton").click(function () {
var PARTNER = $("#partner").val();
var HI = $("#hi").val();
var LIMIT = $("#limit").val();
var PAGE = $("#page").val();
var filtersArray = [];

if (PARTNER == 0 && HI == 2 && LIMIT == 20 && (PAGE == 1 || PAGE == undefined)) {
	window.location = "/administrator/tag";
} else {
	if (HI != 2) {
		filtersArray[filtersArray.length] = "hi="+HI;
	}
	
	if (PARTNER != 0) {
		filtersArray[filtersArray.length] = "partner="+PARTNER;
	}
	
	if (LIMIT != 20) {
		filtersArray[filtersArray.length] = "limit="+LIMIT;
	}
	
	if (PAGE != 1 && PAGE != undefined) {
		filtersArray[filtersArray.length] = "page="+PAGE;
	}

	window.location = "/administrator/tag?"+filtersArray.join("&");
} 
});
</script>

<table id="externals">
	<colgroup>
		<col id="Tag Name"></col>
		<col id="Internal Tag"></col>
		<col id="Partner"></col>
		<col id="Modify"></col>
	</colgroup>
	<tbody id="rows">
		@if (!is_null($helperDataJson['tagList']))
			<tr>
			<td>Tag Name</td>
			<td>Internal Tag</td>
			<td>Partner</td>
			<td>Modify</td>
			</tr>
			@foreach($helperDataJson['tagList'] as $tag)
				@if (isset($tag))
					<tr>
						<td>{{$tag['external_tag_name']}}</td>
						<td>{{$tag['internal_tag_name']}}</td>
						<td>{{$tag['partner_name']}}</td>
						<td><a href={{route('/administrator/tag/mod/{id}', array('id' => "ext__".$tag['external_tag_id']))}}>Click</a></td>
					</tr>
				@endif
			@endforeach
		@endif
	</tbody>
</table>
@endif
@endif





