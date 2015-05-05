<section>
<div class="wrapper">
<span><a href="/">LiveChannel Logo</a></span>
@if ($headerDatas['isAuth'])
	{{$headerDatas['user']->email}}
	<a href="/logout?admin=0">Kijelentkezés</a>
@else
	<a href="/login">Bejelentkezés</a>
	<a href="/registration">Regisztráció</a>

@endif
<div class="search" id="search" name="search">
	<form method="GET" action="/search">
		<div id="search-input-wrapper" class="form">
			<input type="text" id="search-input" name="search_input" autocomplete="off" />
			<label for="search-input" class="hide">Search</label>
			<button type="submit">
				<i class="icon search-icon">
					::before
				</i>
			</button>
		</div>
		<div id="search-dropdown-wrapper" class="suggession hide">
			<ul id="search-suggession-wrap" class="suggession stars"></ul>
			<ul id="recent-search-wrap" class="suggession">
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
	<script>
		
		function clearDataFromSearchList(element) {
			$("#"+element).html("");
		}
		
		function addStarToDiv(element, star) {
			$("#"+element).append('<li class="list-title">Stars</li>');
			foreach(star as s) {
				$("#"+element).append('<li class="search-star-item"><a data-key="'+s['star_name']+'" href="/search?star='+s['star_id']+'">'+s['star_name']+'</a></li>')
			}
		}
		
		function addTagToDiv(element, tag) {
			$("#"+element).append('<li class="list-title">Tags</li>');
			foreach(tag as t) {
				$("#"+element).append('<li class="search-star-item"><a data-key="'+t['internal_tag_name']+'" href="/search?tag='+t['internal_tag_id']+'">'+t['internal_tag_name']+'</a></li>')
			}
		}
		
		function setDatasToWrapper(element, star, tag) {
			var hasList = false;
			clearDataFromSearchList(element);
			if (star.length) {
				hasList = true;
				addStarToDiv(element, star);
			}
			if (tag.length) {
				hasList = true;
				addTagToDiv(element, tag);
			}
			if ($('#search-dropdown-wrapper').hasClass('hide')) {
				$('#search-dropdown-wrapper').removeClass('hide');
			}
		}
		
		function getStarDatas(text) {
			var datastring = 'tx='+text;
			$.ajax({
				type: "POST",
				url: "/getsearch/getstars",
				data: datastring,
				success: function(data){
					return data;
				}
			},"json");
		}
		
		function getTagDatas(text) {
			var datastring = 'tx='+text;
			$.ajax({
				type: "POST",
				url: "/getsearch/gettag",
				data: datastring,
				success: function(data){
					return data;
				}
			},"json");
		}
		
		$("#search-input").keypress(function() {
			if($(this).val().length > 2) {
				// generate list & show with class
				var starDatas = getStarDatas($(this).val());
				var tagDatas = getTagDatas($(this).val());
				setDatasToWrapper('recent-search-wrap', starDatas, tagDatas);
			} else {
				// delete list & hide	with class
				clearDataFromSearchList("recent-search-wrap");
				if (!$("#search-dropdown-wrapper").hasClass('hide')) {
					$("#search-dropdown-wrapper").addClass('hide');
				}
				
			}
		});
	</script>
</div>
</div>
</section>