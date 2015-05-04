
@if($helperDataJson['class'] != "")
<section class="content-block group {{$helperDataJson['class']}}">
@else	
<section class="content-block group">		
@endif	
	
	<div class="wrapper group" id="{{$helperDataJson['proposer_id']}}">
	
		<!-- Content block header -->
		<div class="content-block-header group">

			<!-- Title -->
			@if($helperDataJson['need_title'])
				<div class="title">
					<h1>
						{{$helperDataJson['title']}}
					</h1>
				</div>
			@endif	
			<!-- End of Title -->

			<!-- More link -->
			@if ($helperDataJson['need_more'])
				<div class="more-link">
					<a href="/{{$helperDataJson['more_link']}}"><span>More</span></a>
				</div>
			@endif
			<!-- End of More link -->			

			<!-- Propmenu -->
			@if($helperDataJson['need_menu'])
				<div class="prop-menu">
					@foreach($helperDataJson['menu_array'] as $link)
						@if (isset($link[0]['name']) && isset($link[0]['title']))
							<a href="/{{$link[0]['name']}}"><span>{{$link[0]['title']}}</span></a>
						@endif
					@endforeach
				</div>
			@endif
			<!-- End of Propmenu -->

		</div>
		<!-- End of Content block header -->

		<!-- Videos -->
		<div class="content-block-videos">
			<?php $n=0; $forcount = $helperDataJson['video_num']+$helperDataJson['adv_num']+1; $bI = rand(1,2); 
				$bannerLink = array(1 => "/star/Miko%20Sinz/3033", 2 => "/category/teen/28");
				$pictureBase = array(1 => "/img/mikobanner", 2 => "/img/teenbanner");
			?>
			@for($i = 1;$i < $forcount; $i++)
				<?php 
					if($helperDataJson['class'] == "featured" && $i < 4) {
						$videoClass = "video-xl";
						$bannerAfter = "310h.png";
					} else {
						$videoClass = "video-l";
						$bannerAfter = "s.png";
					}
				?>
				@if(in_array($i, $helperDataJson['adv_pos']))
					<div class="one-banner {{$videoClass}}"><a href="{{$bannerLink[$bI]}}"><img src="{{$pictureBase[$bI].$bannerAfter}}" /></a></div>
					<?php if ($bI == 1) $bI = 2; else $bI = 1; ?>
				@else
					
				

				<!-- One video preview -->
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
                                                        onmouseover="enableNextImgSrc({{$videoData['video_id']}}, {{$helperDataJson['proposer_id']}});" 
							onmouseout="disableNextImgSrc({{$videoData['video_id']}}, {{$helperDataJson['proposer_id']}});" 
						/>
                                                </a>
						<script>
							needNext[{{$videoData['video_id']}}+'_'+{{$helperDataJson['proposer_id']}}] = false;
							maxThumbs[{{$videoData['video_id']}}+'_'+{{$helperDataJson['proposer_id']}}] = {{$videoData['thumbsCount']}};
							nextThumbs[{{$videoData['video_id']}}+'_'+{{$helperDataJson['proposer_id']}}] = 1;
							thumbArray[{{$videoData['video_id']}}+'_'+{{$helperDataJson['proposer_id']}}] = new Array();
							defaultImgSrc[{{$videoData['video_id']}}+'_'+{{$helperDataJson['proposer_id']}}] = "{{$videoData['default_thumb']}}";
							@foreach($videoData['thumbs'] as $thumb)
								idLength = thumbArray[{{$videoData['video_id']}}+'_'+{{$helperDataJson['proposer_id']}}].length;
								thumbArray[{{$videoData['video_id']}}+'_'+{{$helperDataJson['proposer_id']}}][idLength] = "{{$thumb}}";
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
				@endif
				<!-- End of One video preview -->

			@endfor
		</div>
		<!-- End of Videos -->


		<!-- Pager -->
		@if($helperDataJson['need_pager'])
			<div class="pager group" id="pager__{{$helperDataJson['proposer_id']}}">

				
				@if($helperDataJson['act_page'] > 1)
					<a href="{{$helperDataJson['pagerBase']}}page=1" class="page-link"><i class="fa fa-lg fa-angle-double-left"></i></a>
					<a href="{{$helperDataJson['pagerBase']}}page={{$helperDataJson['act_page']-1}}" class="page-link"><i class="fa fa-lg fa-angle-left"></i></a>
				@endif

				
				@if($helperDataJson['act_page'] > 3)
					<a href="{{$helperDataJson['pagerBase']}}page={{$helperDataJson['act_page']-3}}" class="page-link"><i class="fa fa-lg ">{{$helperDataJson['act_page']-3}}</i></a>
				@endif

				@if($helperDataJson['act_page'] > 2)
					<a href="{{$helperDataJson['pagerBase']}}page={{$helperDataJson['act_page']-2}}" class="page-link"><i class="fa fa-lg">{{$helperDataJson['act_page']-2}}</i></a>
				@endif

				@if($helperDataJson['act_page'] > 1)
					<a href="{{$helperDataJson['pagerBase']}}page={{$helperDataJson['act_page']-1}}" class="page-link"><i class="fa fa-lg">{{$helperDataJson['act_page']-1}}</i></a>
				@endif
				
				
				<span class="actual-page">
					{{$helperDataJson['act_page']}}
				</span>

				@if($helperDataJson['act_page'] < $helperDataJson['maxpage'])
					<a href="{{$helperDataJson['pagerBase']}}page={{$helperDataJson['act_page']+1}}" class="page-link"><i class="fa fa-lg">{{$helperDataJson['act_page']+1}}</i></a>
				@endif
				
				@if($helperDataJson['act_page']+1 < $helperDataJson['maxpage'])
					<a href="{{$helperDataJson['pagerBase']}}page={{$helperDataJson['act_page']+2}}" class="page-link"><i class="fa fa-lg">{{$helperDataJson['act_page']+2}}</i></a>
				@endif
				@if($helperDataJson['act_page']+2 < $helperDataJson['maxpage'])
					<a href="{{$helperDataJson['pagerBase']}}page={{$helperDataJson['act_page']+3}}" class="page-link"><i class="fa fa-lg">{{$helperDataJson['act_page']+3}}</i></a>
				@endif

				
				
				@if($helperDataJson['act_page'] < $helperDataJson['maxpage'])
					<a href="{{$helperDataJson['pagerBase']}}page={{$helperDataJson['act_page']+1}}" class="page-link"><i class="fa fa-lg fa-angle-right"></i></a>
					<a href="{{$helperDataJson['pagerBase']}}page={{$helperDataJson['maxpage']}}" class="page-link"><i class="fa fa-lg fa-angle-double-right"></i></a>
				@endif

				
			</div>
		@endif
		<!-- End of Pager -->

		<!-- More link -->
		@if ($helperDataJson['need_more'])
			<div class="content-block-header group">
			<div class="more-link">
				<a href="/{{$helperDataJson['more_link']}}"><span>More</span></a>
			</div>
			</div>
		@endif
		<!-- End of More link -->		

	</div>
</section>
