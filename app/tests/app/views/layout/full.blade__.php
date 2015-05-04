@extends('layout')

@section('head')
	<title>
		{{{ $pagetitle or 'This is a bigining of a beautiful friendship' }}}
	</title>
	<meta charset="UTF-8" />

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
	<header>
		@if (isset($headerView) && isset($headerDatas) ) 
			@include($headerView, array('headerDatas' => $headerDatas))
		@else
			<div class='header'>This is an empty header</div>
		@endif
	</header>
	<div class='content'>
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
	</div>
	<footer>
		@if (isset($footerView) && isset($footerDatas) ) 
			@include($footerView, array('footerDatas' => $footerDatas))
		@else
			<div class='footer'>This is an empty footer</div>
		@endif
	</footer>
@stop