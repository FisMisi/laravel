<h1>Proposers</h1>
<div>
	<a href={{route('/administrator/proposer/{id}', array('id' => 0))}}>New</a>
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
<span id="searchbutton" name="searchbutton"><u>Search</u></span>
<script>
$("#searchbutton").click(function () {
var LIMIT = $("#limit").val();
var PAGE = $("#page").val();
var filtersArray = [];

if (LIMIT == 20 && (PAGE == 1 || PAGE == undefined)) {
	window.location = "/administrator/proposer";
} else  {
	if (LIMIT != 20) {
		filtersArray[filtersArray.length] = "limit="+LIMIT;
	}
	
	if (PAGE != 1 && PAGE != undefined) {
		filtersArray[filtersArray.length] = "page="+PAGE;
	}

	window.location = "/administrator/proposer?"+filtersArray.join("&");
} 
});
</script>
<table id="proposers">
	<colgroup>
		<col id="name"></col>
		<col id="title"></col>
		<col id="where"></col>
		<col id="order"></col>
		<col id="modify"></col>
	</colgroup>
	<tbody id="rows">
		@if (!is_null($helperDataJson['proposers']))
			<tr>
			<td>Name</td>
			<td>Title</td>
			<td>Where</td>
			<td>Order</td>
			<td>Modify</td>
			</tr>
			@foreach($helperDataJson['proposers'] as $proposer)
				@if (isset($proposer))
					<tr>
						<td>{{$proposer->name}}</td>
						<td>{{$proposer->title}}</td>
						<td>{{$proposer->where_sql}}</td>
						<td>{{$proposer->order_sql}}</td>
						<td><a href={{route('/administrator/proposer/{id}', array('id' => $proposer->proposer_type_id))}}>Modify</a></td>
					</tr>
				@endif
			@endforeach
		@endif
	</tbody>
</table>