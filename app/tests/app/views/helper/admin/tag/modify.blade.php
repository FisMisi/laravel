@if ($helperDataJson['group'])
<div>
<h1>Tag Group</h1>
</div>
<form action="/savetaggroup" method="POST">
<input type="hidden" name="type" id="type" value="gr" />
@if(count($helperDataJson['Group']) == 0)
	<input type="hidden" name="tag_group_id" id="tag_group_id" value="0"/>
	<div>
		<strong>
		Group Name:
		</strong>
		<input type="text" name="tag_group_name" id="tag_group_name" value="" />
	</div>
	<div>
		<strong>
		Group Name:
		</strong>
		<input type="text" name="pos" id="pos" value="999" />
	</div>
	<div>
	<strong>Active</strong>
	<select id="active" name="active">
		<option value="1">Active</option>
		<option value="0">Inactive</option>
	</select>
	</div>
@else
	<input type="hidden" name="tag_group_id" id="tag_group_id" value="{{$helperDataJson['Group']['tag_group_id']}}"/>
	<div>
		<strong>
		Group Name:
		</strong>
		<input type="text" name="tag_group_name" id="tag_group_name" value="{{$helperDataJson['Group']['tag_group_name']}}" />
	</div>
	<div>
		<strong>
		Group Name:
		</strong>
		<input type="text" name="pos" id="pos" value="{{$helperDataJson['Group']['pos']}}" />
	</div>
	<div>
	<strong>Active</strong>
	<select id="active" name="active">
		<option value="1" @if($helperDataJson['Group']['active'] == 1) selected @endif >Active</option>
		<option value="0" @if($helperDataJson['Group']['active'] == 0) selected @endif >Inactive</option>
	</select>
	</div>
@endif
<input type="submit" name="Submit" value="Save" />
</form>

@elseif ($helperDataJson['internal'])
<div>
<h1>Internal Tag</h1>
</div>
<form action="/saveinttag" method="POST">
<input type="hidden" name="type" id="type" value="int"/>
@if(count($helperDataJson['Tag']) == 0)
<input type="hidden" name="internal_tag_id" id="internal_tag_id" value="0"/>
<div>
<strong>Group:</strong>
<select id='category_group' name='category_group' >
@foreach($helperDataJson['groups'] as $key => $value)
<option value="{{$key}}" >{{$value}}</option>
@endforeach
</select>
</div>
<div>
<strong>Tag Name:</strong>
<input type="text" name="internal_tag_name" id="internal_tag_name" value="" />
</div>
<div>
<strong>SEO Name:</strong>
<input type="text" name="internal_tag_seo_name" id="internal_tag_seo_name" value="" />
</div>
<div>
<strong>Viewed: -</strong>
</div>
<div>
<strong>Pos:</strong>
<input type="text" name="pos" id="pos" value="999" />
</div>
<div>
<strong>Megjelenés</strong>
<select id="active" name="active">
	<option value="0">Nem jelenik meg</option>
	<option value="1">Megjelenik</option>
	<option value="2">Tilt</option>
</select>
</div>
<input type="submit" name="Submit" value="Save" />
</form>
@else
<input type="hidden" name="internal_tag_id" id="internal_tag_id" value="{{$helperDataJson['tagId']}}"/>
<div>
<strong>Group:</strong>
<select id='category_group' name='category_group' >
@foreach($helperDataJson['groups'] as $key => $value)
<option value="{{$key}}" @if($key == $helperDataJson['Tag'][0]['category_group']) selected @endif >{{$value}}</option>
@endforeach
</select>
</div>
<div>
<strong>Tag Name:</strong>
<input type="text" name="internal_tag_name" id="internal_tag_name" value="{{$helperDataJson['Tag'][0]['internal_tag_name']}}" />
</div>
<div>
<strong>SEO Name:</strong>
<input type="text" name="internal_tag_seo_name" id="internal_tag_seo_name" value="{{$helperDataJson['Tag'][0]['internal_tag_name']}}" />
</div>
<div>
<strong>Viewed: {{$helperDataJson['Tag'][0]['see_count']}}</strong>
</div>
<div>
<strong>Pos:</strong>
<input type="text" name="pos" id="pos" value="{{$helperDataJson['Tag'][0]['pos']}}" />
</div>
<div>
<strong>Megjelenés</strong>
<select id="active" name="active">
	<option value="0" @if($helperDataJson['Tag'][0]['active'] == 0) selected @endif >Nem jelenik meg</option>
	<option value="1" @if($helperDataJson['Tag'][0]['active'] == 1) selected @endif >Megjelenik</option>
	<option value="2" @if($helperDataJson['Tag'][0]['active'] == 2) selected @endif >Tilt</option>
</select>
</div>
<input type="submit" name="Submit" value="Save" />
</form>
<form action="/deleteexternalfromint" method="POST">
<input type="hidden" name="type" id="type" value="int"/>
<h2>Remove External Tag</h2>
<input type="hidden" name="type" id="type" value="int"/>
<input type="hidden" name="internal_tag_id" id="internal_tag_id" value="{{$helperDataJson['tagId']}}" />
<select id="external_tag_id" name="external_tag_id">
@foreach($helperDataJson['externals'] as $ext)
	<option value="{{$ext['external_tag_id']}}">{{$ext['external_tag_name']}} {{$ext['partner_name']}}</option>
@endforeach
</select>
<input type="submit" name="Submit" value="Delete" />
</form>
<h2>Add external</h2>
<form action="/addexternaltoint" method="POST">
<input type="hidden" name="type" id="type" value="int"/>
<input type="hidden" name="internal_tag_id" id="internal_tag_id" value="{{$helperDataJson['tagId']}}" />
<select id="external_tag_id" name="external_tag_id">
@foreach($helperDataJson['exttoadd'] as $ext)
	<option value="{{$ext['external_tag_id']}}">{{$ext['external_tag_name']}} {{$ext['partner_name']}}</option>
@endforeach
</select>
<input type="submit" name="Submit" value="Add" />

</form>
@endif


@else
<div>
<h1>External Tag</h1>
<input type="hidden" name="type" id="type" value="ext"/>
</div>
<div>
Partner: <strong>{{$helperDataJson['Tag'][0]['partner_name']}}</strong>
</div>
<div>
Tag Name: <strong>{{$helperDataJson['Tag'][0]['external_tag_name']}}</strong>
</div>
<div>
@if(is_null($helperDataJson['Tag'][0]['internal_tag_name']))
<form action="/addexternaltoint" method="POST">
<input type="hidden" name="type" id="type" value="ext"/>
Internal Tag:<select id='internal_tag_id' name='internal_tag_id'>
<option value='0'> </option>
@foreach($helperDataJson['internals'] as $internal)
	<option value="{{$internal['internal_tag_id']}}">{{$internal["internal_tag_name"]}}</option>
@endforeach
</select>
<input type="submit" name="Submit" value="Add" />
<input type="submit" name="Submit" value="Add" />
@else
<form action="/deleteexternalfromint" method="POST">
<input type="hidden" name="type" id="type" value="ext"/>
Internal Tag: <strong>{{$helperDataJson['Tag'][0]['internal_tag_name']}}</strong>
<input type="hidden" name="internal_tag_id" id="internal_tag_id" value="{{$helperDataJson['Tag'][0]['internal_tag_id']}}" />
@endif

<input type="hidden" name="external_tag_id" id="external_tag_id" value="{{$helperDataJson['tagId']}}" />
<input type="submit" name="Submit" value="Remove" />
</form>
</div>
@endif