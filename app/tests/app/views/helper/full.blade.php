@extends('layout')

@section('head')
	<title>
		{{{ $pagetitle or 'This is a begining of a beautiful friendship' }}}
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
			@if (isset($footerView) && isset($footerKey) && isset($footerDatas) ) 
				@include($footerView, array($footerKey => $footerDatas))
			@else
				<p>This is an empty footer</p>
			@endif
		</div>
	</footer>
	<!-- End of Footer -->

	<!-- Panic button -->
	<div id="panic-button"><a href="#"><i class="fa fa-lg fa-hand-o-right"></i>Panic button</a></div>
	<div id="panic-image"><img src="{{URL::asset('img/panic_image.jpg')}}"/></div>
	<!-- End of Panic button -->

@stop