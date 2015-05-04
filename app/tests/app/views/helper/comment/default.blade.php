<section class="content-block group comments">
	<div class="wrapper">
		<div class="comments-container">

			<input type="hidden" name="video_id" id="video_id" value="{{$helperDataJson['video_id']}}" />
			<input type="hidden" name="page" id="page" value="{{$helperDataJson['actualpage']}}" />
			<input type="hidden" name="pages" id="pages" value="{{$helperDataJson['pages']}}" />
			<div class="comments-header">
				<h2>Comments ({{$helperDataJson['commentnum']}})</h2>
				<a href="javascript:void(0);" id="ccom" onclick="$('#fullcomment').slideUp(250);$('#ocom').show();$('#ccom').hide();" class="open-close"  style="display:none;"><i class="fa fa-lg fa-chevron-up"></i></a>
				<a href="javascript:void(0);" id="ocom" onclick="$('#fullcomment').slideDown(250);$('#ccom').show();$('#ocom').hide();" class="open-close" ><i class="fa fa-lg fa-chevron-down"></i></a>
			</div>
			<div id="fullcomment" style="display:none;">
			<div class="content" id="allcomment" name="allcomment">
				@foreach($helperDataJson['comments'] as $comment)
					<div class="one-comment">
						<div class="user">{{$comment['nick']}}</div>
						<div class="time">{{$comment['created_at']}}</div>
						<div class="comment">{{$comment['comment']}}</div>
						
					</div>
				@endforeach
			</div>

			<div class="page-number">
				<span id="pcp" name="pcp" style="display:none;">prew</span>
				<span name="actualpage" id="actualpage">{{$helperDataJson['actualpage']}} </span>
				/ <span name="maxpage" id="maxpage">{{$helperDataJson['pages']}} </span>
				Pages
				<span id="ncp" name="ncp" @if(!$helperDataJson['needNext']) style="display:none;" @endif >next</span>
			</div>

			<div class="new-comment">
				@if($helperDataJson['hasUser'])
					@if($helperDataJson['hasNick'])
						<textarea  rows="5" cols="60" name="newcomment" id="newcomment" ></textarea>
						<div id="sendcomment" name="sendcomment">Send comment</div>
					@else
						<span>
							You have to set Your nick to send comment.<br/> 
							You can do it in Your <a href="/profil">profil page</a>.
						</span>
					@endif
				@else
					<span>
						You need to <a id="comlog" href="javascript:void(0);">Login</a> or <a id="comreg" href="javascript:void(0);" >Registration</a> to send comment.
					</span>
				@endif
			</div>
			</div>
			
		</div>
	</div>
</section>
