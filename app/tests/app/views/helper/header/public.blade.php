
<!-- Header top part -->
<div class="login-search">
	<div class="wrapper group">

		<!-- Login / out / register -->
		@if ($headerDatas['isAuth'])
			<span class="user-name">{{$headerDatas['user']->email}}</span>
			<a href="/profil" class="profil">Profile</a>
			<a href="/logout?admin=0" class="logout"><i class="fa fa-lg fa-sign-out"></i>Sign out</a>
		@else
			<a href="javascript:void(0);" onclick="showReg();" class="register">Become a member!</a>
			<a href="javascript:void(0);" onclick="showLogin();" class="login">
				<i class="fa fa-lg fa-sign-in"></i>Login</a>			
		@endif
		<!-- END of Login / out / register -->

		<!-- Login popup wrapper -->
		<div class="auth hide" id="authdiv" name="authdiv" style="display:none;"></div>
		<!-- END of Login popup wrapper -->

		<!-- Search -->
		<div class="search" id="search" name="search">
			<form id="searchform" name="searchform">
				<div id="search-input-wrapper" class="form">
					<input type="text" id="search-input" name="search-input" autocomplete="off" onblur="searchblur();" onfocus="searchfocus();" placeholder="Search in more than 2 000 000 videos!" />
					<button type="submit"><i class="fa fa-search fa-lg"></i></button>
				</div>
				<div id="search-dropdown-wrapper" class="suggestion hide">

					<ul id="search-suggestion-wrap" class="suggestion stars"></ul>
					
					<ul id="recent-search-wrap" class="suggestion">
					<!--li, list-title-star, search-star-item; list-title-tag, search-tag-item -->
					<!--
						<li class="list-title">Stars</li>
					
						<li class="search-star-item">
							<a data-key="<name>" href="/search?star=<id>"><name>
								<span class="badge right"><count></span>#opcional
							</a>
						</li>
						
						<li class="list-title">Tags</li>
					
						<li class="search-tag-item">
							<a data-key="<name>" href="/search?tag=<id>"><name>
								<span class="badge right"><count></span>#opcional
							</a>
						</li>
					-->
					</ul>
				</div>
			</form>
			<script type="text/javascript">
				$(function() {
					$('#searchform').submit(function(){
						var sda = $('#search-input').val();
						if (sda.length > 2) {
							window.location = '/search/'+$('#search-input').val();
						}
						return false;
					});
				});
			</script>
		</div>
		<!-- END of Search -->

	</div>
</div>
<!-- END of Header top part -->

<!-- Logo + menu -->
<div class="logo-menu">
	<div class="wrapper group">

		<!-- Logo -->
		<div class="logo-wrapper"><a href="/" class="logo"><img src="{{URL::asset('img/LiveRuby_logo.png')}}" /></a></div>
		<!-- END of Logo -->

		<!-- Main menu -->
		<div class="navbar">
			<ul class="menu">
				@foreach($headerDatas['menuItems'] as $mi)
					@if($mi['show'])
						<li class="menulink" @if($mi['name'] == "categories") id="{{$mi['name']}}" name="{{$mi['name']}}" onmouseover="showC();" onmouseout="showC();" @endif >
							{{$mi['a']}}
						</li>
					@endif
				@endforeach
			</ul>
		</div>
		<!-- END of Main menu -->

	</div>
</div>
<!-- END of Logo + menu -->


<!-- Category fullwidth dropdown -->
<div class="cat-dropdown" onmouseover="showC();" onmouseout="showC();" >
	<div class="wrapper">

		<div id="cat-dropdown-wrapper" class="hide">
			<div class="top group">
				<h2 class="cat-list-title">Top Categories</h2>
				<ul>					
					@foreach($headerDatas['catgroup']['top'] as $cat)
						<li class="cat-tag-item">
							<a data-key="{{$cat['internal_tag_name']}}" href="/category/{{$cat['internal_tag_name']}}/{{$cat['internal_tag_id']}}">
								{{$cat['internal_tag_name']}}
							</a>
						</li>
					@endforeach
				</ul>
			</div>
			<div class="all">
				<ul>
					@foreach($headerDatas['catgroup']['all'] as $cat)
						<li class="cat-tag-item">
							<a data-key="{{$cat['internal_tag_name']}}" href="/category/{{$cat['internal_tag_name']}}/{{$cat['internal_tag_id']}}">
								{{$cat['internal_tag_name']}}
							</a>
						</li>
					@endforeach
				</ul>
			</div>				
		</div>


		<script>$('#cat-dropdown-wrapper').hide();</script>
	</div>
</div>
<!-- END of Category dropdown -->




