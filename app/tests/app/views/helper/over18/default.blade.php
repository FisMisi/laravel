<div class="over18">
	<div class="wrapper">
	    <div class="logo"><img src="{{URL::asset('img/LiveRuby_logo_b.png')}}" /></div>
		<h2 class="warning">WARNING: This website contains explicit adult material.</h2>
		<p>You can only proceed <strong>if you are at least 18 years of age</strong> or have reached the
		 age of maturity in the country that you are accessing this website from. 
		 If you do not	meet these requirements, then you do not have permission to access
		  the content on this site and you should leave right now.</p>
	    <p class="buttons">
			<span class="button enter">{{ HTML::linkRoute('/setover18', 'Enter') }}</span>
			<span class="button"><a href="javascript:void(0);">Exit</a></span>
			
			<script>
function closeMe()
{
var win=window.open("http://nemzetisport.hu","_self");
//win.close();
}
</script>
		</p>
	</div>
</div>