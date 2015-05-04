<h1>Videos</h1>
<div class="export">
	<a href="/administrator/videodownload">Export</a>
</div>

Active From Tags:
<select id="active" name="active">
	<option value="3" @if (3 == $helperDataJson['active']) selected="selected" @endif >Mind</option>
	<option value="2" @if (2 == $helperDataJson['active']) selected="selected" @endif >Tilt</option>
	<option value="1" @if (1 == $helperDataJson['active']) selected="selected" @endif >Megjelenik</option>
	<option value="0" @if (0 == $helperDataJson['active']) selected="selected" @endif >Nem jelenik meg</option>
</select>

Active:
<select id="active2" name="active2">
	<option value="2" @if (2 == $helperDataJson['active2']) selected="selected" @endif >All</option>
	<option value="1" @if (1 == $helperDataJson['active2']) selected="selected" @endif >Active</option>
	<option value="0" @if (0 == $helperDataJson['active2']) selected="selected" @endif >Inactive</option>
</select>

ValidSeo:
<select id="validseo" name="validseo">
	<option value="2" @if (2 == $helperDataJson['validseo']) selected="selected" @endif >Mind</option>
	<option value="1" @if (1 == $helperDataJson['validseo']) selected="selected" @endif >Valid Seo</option>
	<option value="0" @if (0 == $helperDataJson['validseo']) selected="selected" @endif >Invalid Seo</option>
</select>

Partner:
<select id='partner' name='partner'>
	<option value="0" @if (0 == $helperDataJson['partner']) selected="selected" @endif >Mind</option>
	@foreach($helperDataJson['partnerList'] as $partner) 
		<option value="{{$partner['partner_id']}}" @if ($partner['partner_id'] == $helperDataJson['partner']) selected="selected" @endif >{{$partner['partner_name']}}</option>
	@endforeach
</select>

Tags:
<select id='tags' name='tags'>
	<option value="0" @if (0 == $helperDataJson['itid']) selected="selected" @endif >Mind</option>
	@foreach($helperDataJson['internalList'] as $tag) 
		<option value="{{$tag['internal_tag_id']}}" @if ($tag['internal_tag_id'] == $helperDataJson['itid']) selected="selected" @endif >{{$tag['internal_tag_name']}}</option>
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
<div>VideoNum (to Select):{{$helperDataJson['videosCount']}}</div> 
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
var ACTIVE2 = $("#active2").val();
var PARTNER = $("#partner").val();
var ITID = $("#tags").val();
var VALIDSEO = $("#validseo").val();
var LIMIT = $("#limit").val();
var PAGE = $("#page").val();
var filtersArray = [];

if (ACTIVE == 3 && ACTIVE2 == 2 && PARTNER == 0 && ITID == 0 && VALIDSEO == 2 && LIMIT == 20 && (PAGE == 1 || PAGE == undefined)) {
	window.location = "/administrator/video";
} else  {
	if (ACTIVE != 3) {
		filtersArray[filtersArray.length] = "active="+ACTIVE;
	}
	
	if (ACTIVE2 != 2) {
		filtersArray[filtersArray.length] = "active2="+ACTIVE2;
	}
	
	if (LIMIT != 20) {
		filtersArray[filtersArray.length] = "limit="+LIMIT;
	}
	
	if (PAGE != 1 && PAGE != undefined) {
		filtersArray[filtersArray.length] = "page="+PAGE;
	}
	
	if (PARTNER != 0) {
		filtersArray[filtersArray.length] = "partner="+PARTNER;
	}
	
	if (ITID != 0) {
		filtersArray[filtersArray.length] = "itid="+ITID;
	}
	
	if (VALIDSEO != 2) {
		filtersArray[filtersArray.length] = "validseo="+VALIDSEO;
	}
	window.location = "/administrator/video?"+filtersArray.join("&");
} 
});
</script>
<div>
<table id="videos">
	<colgroup>
		<col id="Tag Name"></col>
		<col id="Partner Tag"></col>
		<col id="ValidSeo"></col>
		<col id="Active"></col>
		<col id="Active2"></col>
		<col id="Modify"></col>
	</colgroup>
	<tbody id="rows">
		@if (!is_null($helperDataJson['videos']))
			<tr>
			<td>Title</td>
			<td>Partner</td>
			<td>ValidSeo</td>
			<td>Active From Tags</td>
			<td>Active</td>
			<td>Modify</td>
			</tr>
			@foreach($helperDataJson['videos'] as $video)
			
				@if (isset($video))
					<tr>
						<td>{{$video['video_name']}}</td>
						<td>{{$video['partner_name']}}</td>
						<td>@if ($video['valid_seo']) Valid Seo @else Invalid Seo @endif</td>
						<td>@if ($video['active'] == 2) Tiltva @endif @if ($video['active'] == 1) Megjelenik @endif @if ($video['active'] == 0) Nem jelenik meg @endif  </td>
						<td>@if ($video['active2'] == 1) Active @else Inactive @endif  </td>
						<td><a href={{route('/administrator/video/{id}', array('id' => $video['video_id']))}}>Click</a></td>
					</tr>
				@endif
			@endforeach
		@endif
	</tbody>
</table>
</div>
