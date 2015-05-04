@if ($helperDataJson['new'])
<h1>Create Proposer</h1>
<form action="/proposersave" method="POST">
<input type="hidden" name="proposer_type_id" id="proposer_type_id" value="0">
<div>
<strong>Name:</strong>
<input type="text" name="name" id="name" />
</div>

<div>
<strong>Title:</strong>
<input type="text" name="title" id="title" />
</div>

<div class="where">
<h2>Where</h2>
	<div class="tags">
	<h3>Tags</h3>
		<div class="all">
			All: <input type="checkbox" name="alltag" value="1" />
		</div>
		<div class="taglist">
			@for($i=0;$i<count($helperDataJson['tags']);$i=$i+5)
				@for($j=0;$j<5;$j++)
					@if ($i+$j < count($helperDataJson['tags']))
						{{$helperDataJson['tags'][$i+$j]['internal_tag_name']}}
						<input type="checkbox" name="wt{{$helperDataJson['tags'][$i+$j]['internal_tag_id']}}" value="{{$helperDataJson['tags'][$i+$j]['internal_tag_id']}}" />
					@endif
				@endfor<br />
			@endfor
		</div>
	</div>
	<div class="rating">
	<h3>Rating</h3>
		<div class="all">
			All: <input type="checkbox" name="allrating" value="1" />
		</div>
		<div class="raitingchoose">
		bigger:<input type="radio" name="rw1" id="rw11" value="b" /> 
		 lower:<input type="radio" name="rw1" id="rw12" value="l" /> 
		 then:<input type="text" name="rw2" id="rw2" />
		</div>
	</div>
	<div class="length">
	<h3>Length<h3>
		<div class="all">
			All: <input type="checkbox" name="alllength" value="1" />
		</div>
		<div>
			Min:<input type="number" name="minh" id="minh" maxlength="1" min="0" max="9" size="1" value="0" />:
			<input type="number" name="minm" id="minm" maxlength="2" min="0" max="59" size="2" value="00" />:
			<input type="number" name="mins" id="mins" maxlength="2" min="0" max="59" size="2" value="01" />
		</div>
		
		<div>
			Max:<input type="number" name="maxh" id="maxh" maxlength="1" min="0" max="9" size="1" value="9" />:
			<input type="number" name="maxm" id="maxm" maxlength="2" min="0" max="59" size="2" value="59" />:
			<input type="number" name="maxs" id="maxs" maxlength="2" min="0" max="59" size="2" value="59" />
		</div>
	</div>
</div>
<div class="order">
<h2>Order</h2>
	<div class="order1">
	<h3>Order 1</h3>
		Use: <input type="checkbox" name="ou1" value="1" />
		<select name="o1e" id="o1e">
			<option value="videos.video_id">Upload</option>
			<option value="rating">Rating</option>
			<option value="length">Length</option>
			<option value="see_videos.see_count">Most viewed</option>
		</select>
		<select name="o1d" id="o1d">
			<option value="asc">Asc</option>
			<option value="desc">Desc</option>
		</select>
	</div>
	
	<div class="order2">
	<h3>Order 2</h3>
		Use: <input type="checkbox" name="ou2" value="1" />
		<select name="o2e" id="o2e">
			<option value="videos.video_id">Upload</option>
			<option value="rating">Rating</option>
			<option value="length">Length</option>
			<option value="see_videos.see_count">Most viewed</option>
		</select>
		<select name="o2d" id="o2d">
			<option value="asc">Asc</option>
			<option value="desc">Desc</option>
		</select>
	</div>
	<div class="order3">
	<h3>Order 3</h3>
		Use: <input type="checkbox" name="ou3" value="1" />
		<select name="o3e" id="o3e">
			<option value="videos.video_id">Upload</option>
			<option value="rating">Rating</option>
			<option value="length">Length</option>
			<option value="see_videos.see_count">Most viewed</option>
		</select>
		<select name="o3d" id="o3d">
			<option value="asc">Asc</option>
			<option value="desc">Desc</option>
		</select>
	</div>
</div>
<input type="submit" value="Save" />
</form>
@else 
<h1>Modify Proposer</h1>
<form action="/proposersave" method="POST">
<input type="hidden" name="proposer_type_id" id="proposer_type_id" value="{{$helperDataJson['proposer']['proposer_type_id']}}">
<div>
<strong>Name:</strong>
<input type="text" name="name" id="name" value="{{$helperDataJson['proposer']['name']}}"/>
</div>

<div>
<strong>Title:</strong>
<input type="text" name="title" id="title" value="{{$helperDataJson['proposer']['title']}}"/>
</div>

<div class="where">
<h2>Where</h2>
	<div class="tags">
	<h3>Tags</h3>
		<div class="all">
			All: <input type="checkbox" name="alltag" value="1" @if($helperDataJson['alltag']) checked="checked" @endif />
		</div>
		<div class="taglist">
			@for($i=0;$i<count($helperDataJson['tags']);$i=$i+5)
				@for($j=0;$j<5;$j++)
					@if ($i+$j < count($helperDataJson['tags']))
						{{$helperDataJson['tags'][$i+$j]['internal_tag_name']}}
						<input type="checkbox" name="wt{{$helperDataJson['tags'][$i+$j]['internal_tag_id']}}" value="{{$helperDataJson['tags'][$i+$j]['internal_tag_id']}}" @if (in_array($helperDataJson['tags'][$i+$j]['internal_tag_id'], $helperDataJson['tagsv'])) checked="checked" @endif />
					@endif
				@endfor<br />
			@endfor
		</div>
	</div>
	<div class="rating">
	<h3>Rating</h3>
		<div class="all">
			All: <input type="checkbox" name="allrating" value="1" @if($helperDataJson['allrating']) checked="checked" @endif />
		</div>
		<div class="raitingchoose">
		bigger:<input type="radio" name="rw1" id="rw11" value="b" @if($helperDataJson['rw1'] == "b") checked @endif /> 
		 lower:<input type="radio" name="rw1" id="rw12" value="l" @if($helperDataJson['rw1'] == "l") checked @endif  /> 
		 then:<input type="text" name="rw2" id="rw2" value="{{$helperDataJson['rw2']}}" />
		</div>
	</div>
	<div class="length">
	<h3>Length<h3>
		<div class="all">
			All: <input type="checkbox" name="alllength" value="1" @if($helperDataJson['alllength']) checked="checked" @endif />
		</div>
		<div>
			Min:<input type="number" name="minh" id="minh" maxlength="1" min="0" max="9" size="1" value="{{$helperDataJson['minh']}}" />:
			<input type="number" name="minm" id="minm" maxlength="2" min="0" max="59" size="2" value="{{$helperDataJson['minm']}}" />:
			<input type="number" name="mins" id="mins" maxlength="2" min="0" max="59" size="2" value="{{$helperDataJson['mins']}}" />
		</div>
		
		<div>
			Max:<input type="number" name="maxh" id="maxh" maxlength="1" min="0" max="9" size="1" value="{{$helperDataJson['maxh']}}" />:
			<input type="number" name="maxm" id="maxm" maxlength="2" min="0" max="59" size="2" value="{{$helperDataJson['maxm']}}" />:
			<input type="number" name="maxs" id="maxs" maxlength="2" min="0" max="59" size="2" value="{{$helperDataJson['maxs']}}" />
		</div>
	</div>
</div>
<div class="order">
<h2>Order</h2>
	<div class="order1">
	<h3>Order 1</h3>
		Use: <input type="checkbox" name="ou1" value="1" @if($helperDataJson['ou1']) checked="checked" @endif />
		<select name="o1e" id="o1e">
			<option value="videos.video_id" @if($helperDataJson['o1e'] =="videos.video_id") selected="selected" @endif >Upload</option>
			<option value="rating" @if($helperDataJson['o1e'] =="rating") selected="selected" @endif >Rating</option>
			<option value="length" @if($helperDataJson['o1e'] =="length") selected="selected" @endif >Length</option>
			<option value="see_videos.see_count" @if($helperDataJson['o1e'] =="see_videos.see_count") selected="selected" @endif >Most viewed</option>
		</select>
		<select name="o1d" id="o1d">
			<option value="asc"  @if($helperDataJson['o1d'] =="asc") selected="selected" @endif >Asc</option>
			<option value="desc" @if($helperDataJson['o1d'] =="desc") selected="selected" @endif >Desc</option>
		</select>
	</div>
	
	<div class="order2">
	<h3>Order 2</h3>
		Use: <input type="checkbox" name="ou2" value="1" @if($helperDataJson['ou2']) checked="checked" @endif />
		<select name="o2e" id="o2e">
			<option value="videos.video_id" @if($helperDataJson['o2e'] =="videos.video_id") selected="selected" @endif >Upload</option>
			<option value="rating" @if($helperDataJson['o2e'] =="rating") selected="selected" @endif >Rating</option>
			<option value="length" @if($helperDataJson['o2e'] =="length") selected="selected" @endif >Length</option>
			<option value="see_videos.see_count" @if($helperDataJson['o2e'] =="see_videos.see_count") selected="selected" @endif >Most viewed</option>
		</select>
		<select name="o2d" id="o2d">
			<option value="asc" @if($helperDataJson['o2d'] =="asc") selected="selected" @endif >Asc</option>
			<option value="desc" @if($helperDataJson['o2d'] =="desc") selected="selected" @endif >Desc</option>
		</select>
	</div>
	
	<div class="order3">
	<h3>Order 3</h3>
		Use: <input type="checkbox" name="ou3" value="1" @if($helperDataJson['ou3']) checked="checked" @endif />
		<select name="o2e" id="o2e">
			<option value="videos.video_id" @if($helperDataJson['o3e'] =="videos.video_id") selected="selected" @endif >Upload</option>
			<option value="rating" @if($helperDataJson['o3e'] =="rating") selected="selected" @endif >Rating</option>
			<option value="length" @if($helperDataJson['o3e'] =="length") selected="selected" @endif >Length</option>
			<option value="see_videos.see_count" @if($helperDataJson['o3e'] =="see_videos.see_count") selected="selected" @endif >Most viewed</option>
		</select>
		<select name="o3d" id="o3d">
			<option value="asc" @if($helperDataJson['o3d'] =="asc") selected="selected" @endif >Asc</option>
			<option value="desc" @if($helperDataJson['o3d'] =="desc") selected="selected" @endif >Desc</option>
		</select>
	</div>
</div>
<input type="submit" value="Save" />
</form>


@endif