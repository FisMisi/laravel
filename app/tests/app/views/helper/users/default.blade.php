<div class="wrapper group">
	<h1>User Profil</h1>

	<!-- Profil data -->
	<div class="content-block-50">

		<div class="error" id="moderr" name="moderr"></div>
		<div class='msg' id="modmsg" name="modmsg"></div>

		@if($helperDataJson['confirmed'] || $helperDataJson['admin'])

		<input type="hidden" name="id" id="id" value="{{$helperDataJson['user_id']}}" />

		<div class="userdata">
			<span class="input-label">Email:</span>
			{{$helperDataJson['email']}}
		</div>

		<div class="userdata">
			<span class="input-label">Nick:</span>
			<input type="text" name="nick" id="nick" value="{{$helperDataJson['nick']}}" />
		</div>

		<div class="userdata">
			<span class="input-label">First name:</span>
			 <input type="text" name="firstname" id="firstname" value="{{$helperDataJson['firstname']}}" />
		</div>

		<div class="userdata">
			<span class="input-label">Last name:</span>
			<input type="text" name="lastname" id="lastname" value="{{$helperDataJson['lastname']}}" />
		</div>

		<div class="userdata">
			<span class="input-label">Sex:</span>
			<label><input type="radio" name="sex" id="sex" value='2' @if($helperDataJson['sex'] == 2) checked @endif /><i>Female</i></label>
			<label><input type="radio" name="sex" id="sex" value='1' @if($helperDataJson['sex'] == 1) checked @endif /><i>Male</i></label>
		</div>

		<a href="javascript:void(0);" onclick="modifyUser();" class="submit-button">Modify User</a>

	</div>
	<!-- END of Profil data -->

	<!-- Change password -->
	<div class="content-block-50">

		<h3>Change password</h3>

		<script>
			function modifyUser() {
				var id = $("#id").val();
				var nick = $("#nick").val();
				var firstname = $("#firstname").val();
				var lastname = $("#lastname").val();
				var sex = $('input[name=sex]:checked').val();
				var datastring = 'i='+id+'&n='+nick+'&f='+firstname+'&l='+lastname+'&s='+sex;
				$.ajax({
					type: "POST",
					url: "/moduser",
					data: datastring,
					success: function(data){
						if (data['ferror'] == 1) {
							location.reload();
						}else if(data['error'].length == 0) {
							$("#modmsg").html(data['msg']);
							$("#moderr").html('');
						} else {
							var content = '';
							$.each(data['error'], function(key, value){
								content = content+'<p>'+value+'</p>';
							});
							$("#modmsg").html('');
							$("#moderr").html(content);
						}
					}
				},"json");
			}
		</script>

		<div class="userdata">
			<span class="input-label">Old Password:</span>
			<input type="password" name="oldpasswd" id="oldpasswd" />
		</div>

		<div class="userdata">
			<span class="input-label">New Password:</span>
			<input type="password" name="newpasswd" id="newpasswd" />
		</div>

		<div class="userdata">
			<span class="input-label">New Password 2:</span>
			<input type="password" name="newpasswd2" id="newpasswd2" />
		</div>

		<a href="javascript:void(0);" onclick="modifyPasswd();" class="submit-button">Change password</a>

		<script>
			function modifyPasswd() {
				var id = $("#id").val();
				var oldpasswd = $("#oldpasswd").val();
				var passwd = $("#newpasswd").val();
				var passwd2 = $("#newpasswd2").val();
				var datastring = 'i='+id+'&o='+oldpasswd+'&n='+passwd+'&n2='+passwd2;
				$.ajax({
					type: "POST",
					url: "/modpasswd",
					data: datastring,
					success: function(data){
						if (data['ferror'] == 1) {
							location.reload();
						}else if(data['error'].length == 0) {
							$("#modmsg").html(data['msg']);
							$("#moderr").html('');
						} else {
							var content = '';
							$.each(data['error'], function(key, value){
								content = content+'<p>'+value+'</p>';
							});
							$("#modmsg").html('');
							$("#moderr").html(content);
						}
					}
				},"json");
			}
		</script>
		@else
			<div>You Need to confirm Your registration!</div>
		@endif

	</div>
	<!--  Change password -->

	
</div>