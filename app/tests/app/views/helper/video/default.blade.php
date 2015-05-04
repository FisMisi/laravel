
<section class="content-block featured">
	<div class="wrapper group">
		<div class="video-title-wrapper">
			<h1>{{$helperDataJson['title']}}</h1>	
		</div>	
		<div class="content-wrapper">
			<div class="video-wrapper">
				<div class="video">{{$helperDataJson['iframe']}}</div>
				<div class="video-info">
					<div class="video-tags">
						@foreach($helperDataJson['videotags'] as $videotag)
							<a href="/category/{{$videotag['internal_tag_name']}}/{{$videotag['internal_tag_id']}}" class=""><span class="one-tag">{{$videotag['internal_tag_name']}}</span></a>
						@endforeach
						<!--
						<a href="#" class=""><span class="one-tag">Amateur</span></a>
						<a href="#" class=""><span class="one-tag">MILF</span></a>
						<a href="#" class=""><span class="one-tag">Teen</span></a>
						<a href="#" class=""><span class="one-tag">Blondie</span></a>-->
					</div>
					<div class="video-length">{{$helperDataJson['length']}}</div>
					<div class="video-rating">
					<?php 
						if($helperDataJson['hasrating']){
							if ($helperDataJson['userrating']) {
								$upClass = " done";
								$downClass = "";
							} else {
								$upClass = "";
								$downClass = " done";	
							}
						} else {
							$upClass = "";
							$downClass = "";
						}
					?>
						<a href="javascript:void(0);" onclick="ratingv({{$helperDataJson['video_id']}}, 1);" class="hand{{$upClass}}"><i class="fa fa-3x fa-thumbs-o-up"></i><span>Like</span></a>
						<span class="actual-rate">{{$helperDataJson['rating']}}%</span>
						<a href="javascript:void(0);" onclick="ratingv({{$helperDataJson['video_id']}}, 0);" class="hand down{{$downClass}}"><i class="fa fa-3x fa-thumbs-o-down"></i></a>
						@if($helperDataJson['canpv'] == 1)
							<a href="javascript:void(0);" onclick="adpersvid({{$helperDataJson['video_id']}}, 1);" class="favorite-button">Add to Favorite</a>
						@elseif($helperDataJson['canpv'] == 0)
							<a href="javascript:void(0);" onclick="adpersvid({{$helperDataJson['video_id']}}, 0);" class="favorite-button">Remove from Favorite</a>
						@else 
							<a id="comlog"  class="favorite-button" href="javascript:void(0);">To add this video to your favorites, please log in</a>
						
						@endif
						
						@if ($helperDataJson['isUserAdmin']) 
							<a id="regVidThumbs" class="admin-button" href="javascript:void(0);" onclick="regthumbs({{$helperDataJson['video_id']}});">Regenerate</a>
						@endif
						<p>itt van egy block{{$helperDataJson['isUserAdmin']}}</p>
						{{--<a href="#" class="favorite-button">Favorite</a>--}}
					</div>
				</div>
			</div>
			<div class="banner-wrapper banner-normal">
				Banner1
			</div>
			<div class="banner-wrapper banner-normal">
				Banner2
			</div>			
		</div>	
	</div>
	<div class="wrapper group" id="1">
		<div class="small-header">
			<h3>Related videos</h3>
		</div>
		<div class="content-block-videos">
			<?php $n=0; $forcount = $helperDataJson['video_num']+1;$videoClass = "video-l"; ?>
			@for($i = 1;$i < $forcount; $i++)
				<?php $videoData = $helperDataJson['videos_datas'][$n];$n++; ?>
				<div class="one-video {{$videoClass}}">
					<div class="the-image">
						<?php 
							$link = str_replace("videoid", $videoData['video_id'], $helperDataJson['video_link_pattern']);
							$link = str_replace("videoname", $videoData['video_seo_name'], $link);
							$link = str_replace("video_name", $videoData['video_seo_name'], $link);
						?>
                                                <a href="{{$link}}">
						<img 
							name="{{$videoData['video_id']}}" 
							id="{{$videoData['video_id']}}" 
							src="{{$videoData['default_thumb']}}" 
							onmouseover="enableNextImgSrc({{$videoData['video_id']}}, 1);" 
							onmouseout="disableNextImgSrc({{$videoData['video_id']}}, 1);" 
						/>
                                                </a>
						<script>
							needNext[{{$videoData['video_id']}}+'_'+1] = false;
							maxThumbs[{{$videoData['video_id']}}+'_'+1] = {{$videoData['thumbsCount']}};
							nextThumbs[{{$videoData['video_id']}}+'_'+1] = 1;
							thumbArray[{{$videoData['video_id']}}+'_'+1] = new Array();
							defaultImgSrc[{{$videoData['video_id']}}+'_'+1] = "{{$videoData['default_thumb']}}";
							@foreach($videoData['thumbs'] as $thumb)
								idLength = thumbArray[{{$videoData['video_id']}}+'_'+1].length;
								thumbArray[{{$videoData['video_id']}}+'_'+1][idLength] = "{{$thumb}}";
							@endforeach
						</script>
					</div>
					<div class="info-block group">
						<div class="video-tags">
							@foreach($videoData['tags'] as $key => $tag)
								<a href="/category/{{$tag}}/{{$key}}"><span class="one-tag">{{$tag}}</span></a> 
							@endforeach
						</div>
						<div class="video-name">
							<span>{{$videoData['video_name']}}</span>
						</div>	
						<div class="video-views">
							<span>{{number_format($videoData['see_count'], 0, '.', ',')}} views</span>
						</div>
						<div class="video-data">
						 	<span class="length">{{$videoData['length']}}</span>
						</div>						
						<div class="video-rating">
							<i class="fa fa-heart"></i>
							<?php
								$rating = round($videoData['rating']*20);
							?>
							<span>{{$rating}}%</span>
						</div>

					</div>
				</div>
			@endfor
		</div>
	</div>
</section>
