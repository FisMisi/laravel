<div class="login">
<a href="javascript:void(0);" onclick="showLogin();" class="popup-close" ><i class="fa fa-lg fa-close"></i></a>
<div class="logmsg" name="logmsg" id="logmsg"></div>
<div class="logerror" name="logerror" id="logerror"></div>
<input type="email" name="email" id="email" placeholder="E-mail address" />
<input type="password" name="password" id='password' placeholder="Password" />
<input type="checkbox" name="remember" id="remember" /><label for="remember">Remember Me</label>
<a href="javascript:void(0);" onclick="login();" class="button">Login</a>
<p>If you are not a member, you can <a href="javascript:void(0);" onclick="showReg();"><strong>register here.</strong></a></p>
</div>
<script>
$('div.login #password').keypress(function(e){
	if(e.keyCode==13)
		login();
});

$('div.login a.popup-close').click(function(event){
	console.log('popup close');
	setnhc(0);
	if (logind != 0) {
		showLogin();
	}
	var event = event || window.event || $(window.event);
	if(event.stopPropagation) {
		console.log('elso');
		event.stopPropagation();
	} else if(event.cancelBubble) {
		console.log('masodik');
		event.cancelBubble = true;
	} else {
		console.log('harmadik');
		event.returnValue = false;
	}
	
});

/*$('html').click(function(e) {
{

	
    var container = $("#authdiv");
	return false;
	console.log(container.is(e.target));
	return false;
	
	
});*/

/*
$('html').click(function() {
	console.log(getnhc());
	console.log(logind != 0 && getnhc() == 1);
	if(logind != 0 && getnhc() == 1) {
		logind = 0;
		console.log("html close");
		$("#authdiv").slideUp(250);
	}
	setnhc(1);
});
*/
$('#authdiv').click(function(event){
	setnhc(0);
	console.log("authdiv stop");
	var event = event || window.event || $(window.event);
	if(event.stopPropagation) {
		console.log('elso1');
		event.stopPropagation();
	} else if(event.cancelBubble) {
		console.log('masodik1');
		event.cancelBubble = true;
	} else {
		console.log('harmadik1');
		event.returnValue = false;
	}
});
</script>