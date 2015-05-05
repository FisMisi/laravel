<h1>Comments</h1>
<div class='active'>
Active: <select id="active" name="active">
		<option value="2" @if (2 == $helperDataJson['active']) selected="selected" @endif >All</option>
		<option value="1" @if (1 == $helperDataJson['active']) selected="selected" @endif >Active</option>
		<option value="0" @if (0 == $helperDataJson['active']) selected="selected" @endif >Inactive</option>
	</select>
</div>



<div class="category">
Category: <select id="category" name="category">
		@foreach($helperDataJson['categoryList'] as $key => $value)
			<option value="{{$key}}" @if ($helperDataJson['category'] == $key) select="select" @endif >{{$value}}</option>
		@endforeach
	</select>
</div>

<div class="user">
	UserId: <input type="text" id="user" name="user" value="{{$helperDataJson['user']}}" />
</div>

<div class="video">
	VideoId: <input type="text" id="video" name="video" value="{{$helperDataJson['video']}}" />
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
	var USER = $("#user").val();
	var ACTIVE = $("#active").val();
	var VIDEO = $("#video").val();
	var CATEGORY = $("#category").val();
	var LIMIT = $("#limit").val();
	var PAGE = $("#page").val();
	var filtersArray = [];
	if (ACTIVE == 2 && USER == "" && VIDEO == "" && CATEGORY == null && LIMIT == 20 && (PAGE == 1 || PAGE == undefined)) {
		window.location = "administrator/comment";
	} else {
		if (LIMIT != 20) {
			filtersArray[filtersArray.length] = "limit="+LIMIT;
		}
		
		if (PAGE != 1 && PAGE != undefined) {
			filtersArray[filtersArray.length] = "page="+PAGE;
		}

		if (ACTIVE != 2) {
			filtersArray[filtersArray.length] = "active="+ACTIVE;
		}
		if (CATEGORY != null) {
			filtersArray[filtersArray.length] = "category="+CATEGORY;
		}
		if (VIDEO != "") {
			filtersArray[filtersArray.length] = "video="+VIDEO;
		}
		if (USER != "") {
			filtersArray[filtersArray.length] = "user="+USER;
		}
		window.location = "comment?"+filtersArray.join("&");
	}
});
</script>
</div>

<table id="events">
	<colgroup>
		<col id="active"></col>
		<col id="comment"></col>
		<col id="user_email"></col>
		<col id="inactive_email"></col>
		<col id="inactive_reason"></col>
		<col id="video_name"></col>
		<col id="comment_id"></col>
	</colgroup>
	<tbody id="rows">
		@if (!is_null($helperDataJson['list']))
			<tr>
			<td>Active</td>
			<td>Comment</td>
			<td>User Email</td>
			<td>Inactive User Email</td>
			<td>Inactive Reason</td>
			<td>Video Name</td>
			<td>Modify</td>
			</tr>
			@foreach($helperDataJson['list'] as $comment)
				@if (isset($comment))
					<tr>
						<td>@if ($comment['active']) Active @else Inactive @endif</td>
						<td>{{$comment['comment']}}</td>
						<td>{{$comment['user_email']}}</td>
						<td>{{$comment['inactive_email']}}</td>
						<td>{{$comment['inactive_reason']}}</td>
						<td>{{$comment['video_name']}}</td>
						<td><a href={{route('/administrator/comment/{id}', array('id' => $comment['comment_id']))}}>Modify</a></td>
					</tr>
				@endif
			@endforeach
		@endif
	</tbody>
</table>


