@if (!is_null($helperDataJson['sc'])) 
<h1>Edit Static Content</h1>
<form name="aaa" action="/administrator/postscmodify" method="post">
	<input type="hidden" id="static_content_id" name="static_content_id" value="{{$helperDataJson['sc']->static_content_id}}" />
	Language: <span id="language" name="language">{{$helperDataJson['sc']->language}}
	Class: <span name="class" id="class">{{$helperDataJson['sc']->class}}</span>
	Active: <input type="checkbox" id="active" name="active" value="1" @if ($helperDataJson['sc']->active) checked="checked" @endif />
	<br />
	Title: <input type="text" id="title" name="title" value="{{$helperDataJson['sc']->title}}" />

	<?php
		$text = htmlspecialchars( $helperDataJson['sc']->content ) ;
	?>

	<textarea id="content" name="content" style="width:800px;height:400px;visibility:hidden;"><?php echo $text;?></textarea>
	<input type="hidden" name="do" value="up" />
	<input type="submit" name="Submit" value="Modify" />
</form>

@else
<h1>Create Static Content</h1>

<form name="aaa" action="/administrator/postscmodify" method="post">
	<input type="hidden" id="static_content_id" name="static_content_id" value="0" />
	Class: <select id="class_select" name="class_select" onchange="setLanguageSelect();">
		@foreach($helperDataJson['classes'] as $key => $class)
			<option value="{{$key}}">{{$class}}</option>
		@endforeach
	</select>
<script>
var prefix = 'usable__';
var prefix2= 'usable_long__';
function setLanguageSelect() {
	scValue = document.getElementById("class_select").value;
	eval("feldolgoz="+prefix+scValue);
	eval("feldolgoz2="+prefix2+scValue);
	$("#language option").remove();
	select = document.getElementById("language");
	if (feldolgoz.length > 1) {
		select.options[select.options.length] = new Option('Choose Language', 0);
	}
	$.each(feldolgoz, function(index, value) {
		select.options[select.options.length] = new Option(feldolgoz2[index], value);
	});
	
	if (scValue == 'new_class_sc') {
		$("#sc_name").show();
	} else {
		$("#sc_name").hide();
	}
}
</script>
	<input type="text" name="sc_name" id="sc_name" />
	Language: <select id='language' name='language'>
			<option value="en" >English</option>	
	</select>

	Active: <input type="checkbox" id="active" name="active" value="1" />
	<br />

	Title: <input type="text" id="title" name="title" value="" />
	
	<textarea id="content" name="content" style="width:800px;height:400px;visibility:hidden;"></textarea>
	<input type="hidden" name="do" value="up" />
	<input type="submit" name="Submit" value="Create" />
</form>
@endif