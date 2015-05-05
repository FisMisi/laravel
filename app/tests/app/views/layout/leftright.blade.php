@extends('layout')

@section('head')
	<title>
		{{{ $pagetitle or 'This is a bigining of a beautiful friendship' }}}
	</title>
	<meta charset="UTF-8" />
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<style>
form {
margin: 0;
}
textarea {
display: block;
}
</style>
	<link rel="stylesheet" type="text/css" href="/css/leftright.css" />
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
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
	@if (isset($scripts))
		@foreach($scripts as $script)
			<script>{{$script}}</script>
		@endforeach
	@endif
@stop

@section('body')
	<header>
		@if (isset($headerView) && isset($headerDatas) ) 
			@include($headerView, array('headerDatas' => $headerDatas))
		@else
			<div class='header'>This is an empty header</div>
		@endif	
	</header>
	
	<div class="lffull">
	<div class='left'>
		@if (isset($left))
			@forelse($left as $content)
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
	<div class='right'>
		@if (isset($right))
			@forelse($right as $content)
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
	</div>
	
	<footer>
		@if (isset($footerView) && isset($footerKey) && isset($footerDatas) ) 
			@include($footerView, array($footerKey => $footerDatas))
		@else
			<div class='footer'>This is an empty footer</div>
		@endif
	</footer>
@stop