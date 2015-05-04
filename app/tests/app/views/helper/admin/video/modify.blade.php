<h1>Modify Video</h1>

<form action="/savevideo" method="POST">
<div>
Video Id: {{$helperDataJson['video']->video_id}}
</div>
<input type="hidden" name="video_id" id="video_id" value="{{$helperDataJson['video']->video_id}}" />
<div>
Video Base Id: {{$helperDataJson['video']->video_base_id}}
</div>
<div>
Partner: {{$helperDataJson['partner']}}
</div>

@if ($helperDataJson['video']->valid_seo)
	<div><strong>Video have valid seo link</strong></div>
@else
	<div><strong>Video havn't valid seo link</strong></div>
@endif
<div>
Video Title: 
<input type="text" id="video_name" name="video_name" value="{{$helperDataJson['video']->video_name}}" />
<a href="javascript:void(0);" onclick="gen_vid_seo({{$helperDataJson['video']->video_id}});">Generate Seo Link</a>
</div>
<div>
Video Seo Link: 
<input type="text" id="video_seo_name" name="video_seo_name" value="{{$helperDataJson['video']->video_seo_name}}" />
</div>
<div>
Video Source: {{{$helperDataJson['video']->video_flash_link}}}
</div>
<div>
<a target="_blank" href="/video/{{$helperDataJson['video']->video_seo_name}}/{{$helperDataJson['video']->video_id}}">See Video</a>
</div>
<div>
Video Default Thumb: <a href="{{$helperDataJson['video']->default_thumb}}"  target="_blank">{{{$helperDataJson['video']->default_thumb}}}</a>
</div>
<div>
Video Thumbs:
</div>
@foreach($helperDataJson['thumbs'] as $thumb)
<div><a href="{{$thumb}}" target="_blank">{{{$thumb}}}</a></div>
@endforeach

<div>
Length: {{$helperDataJson['video']->length}}
</div>
<div>
Aktiv: 
<select id="active2" name="active2">
	<option value="1" @if($helperDataJson['video']->active2 == 1) selected @endif >Igen</option>
	<option value="0" @if($helperDataJson['video']->active2 == 0) selected @endif >Nem</option>
</select>

</div>

<div>
Aktiv tagek alapján:
@if ($helperDataJson['video']->active == 0)
Inaktiv
@elseif ($helperDataJson['video']->active == 1)
Aktiv
@else
Locked
@endif
</div>

<div>
Raiting: {{$helperDataJson['video']->sum_rating}}/{{$helperDataJson['video']->rating_number}}
</div>
<div>
Tags: {{$helperDataJson['tags']}}
</div>
<input type="submit" value="Save" />
<script>
function gen_vid_seo(vid) {
	var title = $("#video_name").val();
	var stitle = window.btoa(unescape(encodeURIComponent( title )));
	var datastring = "v="+vid+"&t="+stitle;
	$.ajax({
		type: "POST",
		url: "/vidtoseo",
		data: datastring,
		success: function(data){
			var nseo = decodeURIComponent(escape(window.atob( data )));
			$("#video_seo_name").val(nseo);
		}
	},"json");
}
</script>
</form>

