<section>
<div class="wrapper">
	@foreach($helperDataJson['videos'] as $videoData)
	<span class="fm">
		<?php 
			$link = str_replace("videoid", $videoData['video_id'], $helperDataJson['video_link_pattern']);
			$link = str_replace("videoname", $videoData['video_seo_name'], $link);
			$link = str_replace("video_name", $videoData['video_seo_name'], $link);
		?>
		<img name="{{$videoData['video_id']}}" id="{{$videoData['video_id']}}" src="{{$videoData['default_thumb']}}" onclick="location.href='{{$link}}'" onmouseover="enableNextImgSrc({{$videoData['video_id']}}, 0});" onmouseout="disableNextImgSrc({{$videoData['video_id']}}, 0);" />
		
		<script>
			needNext[{{$videoData['video_id']}}+'_0'] = false;
			maxThumbs[{{$videoData['video_id']}}+'_0'] = {{$videoData['thumbsCount']}};
			nextThumbs[{{$videoData['video_id']}}+'_0'] = 1;
			thumbArray[{{$videoData['video_id']}}+'_0'] = new Array();
			defaultImgSrc[{{$videoData['video_id']}}+'_0'] = "{{$videoData['default_thumb']}}";
			@foreach($videoData['thumbs'] as $thumb)
				idLength = thumbArray[{{$videoData['video_id']}}+'_0'].length;
				thumbArray[{{$videoData['video_id']}}+'_0'][idLength] = "{{$thumb}}";
			@endforeach
		</script>
		{{$videoData['video_name']}} {{$videoData['length']}}
		
		@foreach($videoData['tags'] as $tag)
			{{$tag}} 
		@endforeach
	</span>
	@endforeach
	
	@if($helperDataJson['need_pager'])
	<div class="pager" id="pager__0}}">
		@if($helperDataJson['act_page'] > 1)
			<a href="{{$helperDataJson['pagerBase']}}&page=1">1</a>
		@endif
		@if($helperDataJson['act_page'] > 2)
			<a href="{{$helperDataJson['pagerBase']}}&page={{$helperDataJson['act_page']-1}}">{{$helperDataJson['act_page']-1}}</a>
		@endif
		<span>
			{{$helperDataJson['act_page']}}
		<span>
		@if($helperDataJson['act_page']+1 < $helperDataJson['maxpage'])
			<a href="{{$helperDataJson['pagerBase']}}&page={{$helperDataJson['act_page']+1}}">{{$helperDataJson['act_page']+1}}</a>
		@endif
		@if($helperDataJson['act_page'] < $helperDataJson['maxpage'])
			<a href="{{$helperDataJson['pagerBase']}}&page={{$helperDataJson['maxpage']}}">{{$helperDataJson['maxpage']}}</a>
		@endif
	</div>
@endif
</div>
</section>