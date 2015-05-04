<div class="registration">
<a href="javascript:void(0);" onclick="showReg();" class="popup-close" ><i class="fa fa-lg fa-close"></i></a>
<div class="logmsg" name="logmsg" id="logmsg"></div>
<div class="logerror" name="logerror" id="logerror"></div>
<input type="email" name="email" id="email" value="" placeholder="E-mail address" />
<input type="password" name="password" id="password" placeholder="Password" />
<input type="password" name="password2" id="password2" placeholder="Password - again"/>
<a href="javascript:void(0);" onclick="registration();" class="button">Registration</a>
<p>If You are our member please <a href="void::javascript();" onclick="showLogin();" ><strong>sign in.</strong></a></p>
</div>
<script>
$('div.registration #password2').keypress(function(e){
	console.log(e.keyCode);
	if(e.keyCode==13)
		registration();
});
/*$('html').click(function() {
	logind = 0;
	$("#authdiv").slideUp(250);
});*/

$('#authdiv').click(function(event){
   var event = event || window.event || $(window.event);
	if(event.stopPropagation) {
		event.stopPropagation();
	} else if(event.cancelBubble) {
		event.cancelBubble = true;
	} else {
		event.returnValue = false;
	}
});
</script>