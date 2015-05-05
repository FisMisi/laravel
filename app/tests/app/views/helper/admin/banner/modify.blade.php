<form name="aaa" action="/administrator/bannermodify" method="post">
@if (!is_null($helperDataJson['banner']))
	<h1>Edit Banner</h1>
	<input type="hidden" id="banner_type_id" name="banner_type_id" value="{{$helperDataJson['banner']->banner_type_id}}" />
	Active: <input type="checkbox" id="active" name="active" value="1" @if ($helperDataJson['banner']->active) checked="checked" @endif /><br />
	
	Name: <input type="text" id="name" name="name" value="{{$helperDataJson['banner']->name}}" /><br />
	Title: <input type="text" id="title" name="title" value="{{$helperDataJson['banner']->title}}" /><br />
	Link: <input type="text" id="link" name="link" value="{{$helperDataJson['banner']->link}}" /><br />
	
	Picture src.: <input type="text" id="picture_src" name="picture_src" value="{{$helperDataJson['banner']->picture_src}}" /><br />
	Flash src.: <input type="text" id="flash_src" name="flash_src" value="{{$helperDataJson['banner']->flash_src}}" /><br />
	Iframe src.: <input type="text" id="iframe_src" name="iframe_src" value="{{$helperDataJson['banner']->iframe_src}}" /><br />
	
	Flashvars: <input type="text" id="flashvars" name="flashvars" value="{{$helperDataJson['banner']->flashvars}}" /><br />
@else
	<h1>Create Banner</h1>
	<input type="hidden" id="banner_type_id" name="banner_type_id" value="0" />
	Active: <input type="checkbox" id="active" name="active" value="1" /><br />
	
	Name: <input type="text" id="name" name="name" value="" /><br />
	Title: <input type="text" id="title" name="title" value="" /><br />
	Link: <input type="text" id="link" name="link" value="" /><br />
	
	Picture src.: <input type="text" id="picture_src" name="picture_src" value="" /><br />
	Flash src.: <input type="text" id="flash_src" name="flash_src" value="" /><br />
	Iframe src.: <input type="text" id="iframe_src" name="iframe_src" value="" /><br />
	
	Flashvars: <input type="text" id="flashvars" name="flashvars" value="" /><br />
	
@endif
<input type="submit" name="Submit" value="Save" />
</form>