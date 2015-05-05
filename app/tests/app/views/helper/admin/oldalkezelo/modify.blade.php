<h1>Edit Page</h1>
<h2>Page Datas</h2>
<form id="postroutingmodify" action="/postroutingmodify" method="POST">
@if (!is_null($helperDataJson['routing'])) 
	<input type="hidden" id="routing_id" name="routing_id" value="{{$helperDataJson['routing']->id}}" />
	name: <input type="text" size="60" id="routing_name" name="routing_name" value="{{$helperDataJson['routing']->routing_name}}" />
	routing: <input type="text" size="60" id="routing_path" name="routing_path" value="{{$helperDataJson['routing']->routing_path}}" 
title="Az oldal elérési utja
Lehet dinamikus is pl.:
eszkozok/{valtozo1}:::$s/{valtozo2}:::$c/{valtozo3}:::$d
ahol valtozo1...valtozoX értékeit fel lehet használni a modulokban
$s szoveg (ha nem adjuk meg akkor ez az alapértelmezett)
$c csak betu
$d szám
ui: :::$s/$c/$d egyenlore csak tervezett fejlesztes"	
	/>
	layout: <select name="layout_name" id="layout_name">
		@foreach($helperDataJson['layouts'] as $layout)
			<option value='{{$layout->name}}'  @if($helperDataJson['routing']->layout_name == $layout->name) selected="selected" @endif >{{$layout->title}}</option>
		@endforeach
	</select>
	
	active: <input type="checkbox" id="active" name="active" value="1" @if ($helperDataJson['routing']->active) checked="checked" @endif />
	need over18: <input type="checkbox" id="needover18" name="needover18" value="1" @if ($helperDataJson['routing']->needover18) checked="checked" @endif />
	need auth: <input type="checkbox" id="need_auth" name="need_auth" value="1" @if ($helperDataJson['routing']->need_auth) checked="checked" @endif />
	<input type="submit" value="Modify Routing">
@else 
	<input type="hidden" id="routing_id" name="routing_id" value="0" />
	name: <input type="text" size="60" id="routing_name" name="routing_name" value="" />
	routing: <input type="text" size="60" id="routing_path" name="routing_path" value="" 
title="Az oldal elérési utja
Lehet dinamikus is pl.:
eszkozok/{valtozo1}:::$s/{valtozo2}:::$c/{valtozo3}:::$d
ahol valtozo1...valtozoX értékeit fel lehet használni a modulokban
$s szoveg (ha nem adjuk meg akkor ez az alapértelmezett)
$c csak betu
$d szám
ui: :::$s/$c/$d egyenlore csak tervezett fejlesztes"	
	/>
	layout: <select name="layout_name" id="layout_name">
		@foreach($helperDataJson['layouts'] as $layout)
			<option value='{{$layout->name}}' >{{$layout->title}}</option>
		@endforeach
	</select>
	active: <input type="checkbox" id="active" name="active" value="1" />
	need over18: <input type="checkbox" id="needover18" name="needover18" value="1" />
	need auth: <input type="checkbox" id="need_auth" name="need_auth" value="1" />
	<input type="submit" value="Add Routing">
@endif

</form>
<script>
function changeModul(formname, spamname) {
	if ($('#'+formname+' #modul').val() == 'Custom') {
		$('#'+spamname).show();
	} else {
		$('#'+spamname).hide();
	}
}
</script>
@if (!is_null($helperDataJson['routing'])) 
<h2>Page Contents</h2>
@if (!is_null($helperDataJson['contents']) && count($helperDataJson['contents']) != 0)
<h3>Modify Contents</h3>
	@foreach($helperDataJson['contents'] as $content)
	<form id="postroutingmc___{{$content->content_id}}" action="/postroutingmc" method="POST">
		<input type="hidden" id="content_id" name="content_id" value="{{$content->content_id}}"/>
		<input type="hidden" id="routing_id" name="routing_id" value="{{$helperDataJson['routing']->id}}"/>
		Layout: <input type="text" id="container_name" name="container_name" value="{{$content->container_name}}"/>
		Pos: <input type="text" id="pos" name="pos" value="{{$content->pos}}"/>
		Module: <select name="modul" id="modul" onchange="changeModul('postroutingmc___{{$content->content_id}}', 'helper_span___{{$content->content_id}}');">
			<option value='Custom' @if($content->modul == 'Custom') selected="selected" @endif >Custom</option>
			@foreach($helperDataJson['modules'] as $modul)
				<option value='{{$modul->modul_name}}'  @if($content->modul == $modul->modul_name) selected="selected" @endif >{{$modul->modul_title}}</option>
			@endforeach
		</select>
		
		<span name="helper_span___{{$content->content_id}}" id="helper_span___{{$content->content_id}}" 
			@if($content->modul != 'Custom') style="display: none;" @endif
		>
			Helper: <input type="text" id="helper_class" name="helper_class" value="{{$content->helper_class}}" />
			Function: <input type="text" id="helper_function" name="helper_function" value="{{$content->helper_function}}" />
		</span>
		
		Data: <input type="text" id="helper_data_json" name="helper_data_json" value="{{{$content->helper_data_json}}}"/>
		<input type="submit" value="Modify Content">
	</form>
	@endforeach
@endif
<h3>Add New Content</h3>
<form id="postroutingac" action="/postroutingac" method="POST">
<input type="hidden" id="routing_id" name="routing_id" value="{{$helperDataJson['routing']->id}}"/>
Layout: <input type="text" id="container_name" name="container_name" value=""/>
Pos: <input type="text" id="pos" name="pos" value=""/>
Module: <select name="modul" id="modul" onchange="changeModul('postroutingac', 'helper_span');">
	<option value='Custom' >Custom</option>
	@foreach($helperDataJson['modules'] as $modul)
		<option value='{{$modul->modul_name}}' >{{$modul->modul_title}}</option>
	@endforeach
</select>
<span name="helper_span" id="helper_span">
	Helper: <input type="text" id="helper_class" name="helper_class" value="" />
	Function: <input type="text" id="helper_function" name="helper_function" value="" />
</span>


Data: <input type="text" id="helper_data_json" name="helper_data_json" value=""/><br/>
<input type="submit" value="Add Content">
</form>
@endif