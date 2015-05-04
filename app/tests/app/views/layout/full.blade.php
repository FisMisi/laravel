@extends('layout')

@section('head')
	<title>
		{{{ $pagetitle or 'LiveRuby' }}}
	</title>
	<meta charset="UTF-8" />
	<meta name="robots" content="index, follow" />
	<meta name="Robots" content="All" />
	@if(isset($metaDatas))
		{{$metaDatas}}
	@else
		<meta name="description" content="LiveRuby collects every adult content you might need." />
		<meta name="keywords" content="porn, sex,free porn, ruby, porn videos, sex videos, free pussy, pussy, adult entertainment" />
	@endif
	@if (isset($styleCss))
		@forelse($styleCss as $css) 
			<link rel="stylesheet" type="text/css" href="{{$css}}"/>
		@empty
		@endforelse
	@endif
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	@if (isset($jsLinks)) 
		@forelse($jsLinks as $js) 
			<script type="text/javascript" src="{{$js}}"></script>
		@empty
		@endforelse
	@endif
	<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	ga('create', 'UA-57335467-1', 'auto');
	ga('send', 'pageview');
	</script>
@stop

@section('body')
	

	<!-- Header -->
	<header>
		@if (isset($headerView) && isset($headerDatas) ) 
			@include($headerView, array('headerDatas' => $headerDatas))
		@else
			<div class='wrapper'><p>This is an empty header</p></div>
		@endif
	</header>
	<!-- End of Header -->


	<!-- Main content -->
	@if (isset($contents))
		@forelse($contents as $content)
			@if (isset($content) ) 
				@include($content['view'], array($content['helperDataJson'] => $content['helperData']))
			@else
				<div>Jött valami</div>
			@endif
		@empty
			<div class='content'>
				This is an empty content
			</div>
		@endforelse
	@else
		<div class='content'>
			This is an empty content
		</div>
	@endif
	<!-- End of Main content -->


	<!-- Footer -->
	<footer>
		<div class='wrapper'>
			@if (isset($footerView) && isset($footerDatas) ) 
			@include($footerView, array('footerDatas' => $footerDatas))
		@else
			<div class='footer'>This is an empty footer</div>
		@endif
		</div>
	</footer>
	<!-- End of Footer -->

	<!-- Panic button -->
	<div id="panic-button"><a href="#"><i class="fa fa-lg fa-hand-o-right"></i>Panic button</a></div>
	<div id="panic-image"><img src="{{URL::asset('img/panic_image.jpg')}}"/></div>
	<!-- End of Panic button -->

@stop