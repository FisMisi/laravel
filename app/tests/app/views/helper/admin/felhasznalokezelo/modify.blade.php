<form action="/saveuser" method="POST">
@if (!is_null($helperDataJson['user']))
	<h1>Edit User</h1>
	<input type="hidden" id="user_id" name="user_id" value="{{$helperDataJson['user']->user_id}}" />
	@if ($helperDataJson['user']->admin)
		@if ($helperDataJson['AUisSA'] && $helperDataJson['user']->user_id <> 1) 
			Super admin: <input type="checkbox" id="sa" name="sa" value="1" @if ($helperDataJson['userisSA']) checked="checked" @endif />
		@else
			Super admin: @if($helperDataJson['userisSA']) Yes @else No @endif 
		@endif
	@else
		Confirmed: <input type="checkbox" id="confirmed" name="confirmed" value="1" @if ($helperDataJson['user']->confirmed) checked="checked" @endif />
	@endif
	@if ($helperDataJson['user']->user_id == 1)
		Active: Yes<br />
		Inactive reason: <strong>May not inactive!</strong><br />
	@else
		Active: <input type="checkbox" id="active" name="active" value="1" @if ($helperDataJson['user']->active) checked="checked" @endif /><br />
		Inactive reason: <input type="text" id="inactive_reason" name="inactive_reason" value="{{$helperDataJson['user']->inactive_reason}}" /><br />
	@endif
	
	Email: <input type="text" id="email" name="email" value="{{$helperDataJson['user']->email}}" /><br />
	Nick: <input type="text" id="nick" name="nick" value="{{$helperDataJson['user']->nick}}" /><br />
	Firstname: <input type="text" id="first_name" name="first_name" value="{{$helperDataJson['user']->first_name}}" /><br />
	Lastname: <input type="text" id="last_name" name="last_name" value="{{$helperDataJson['user']->last_name}}" /><br />
	
@else
	<h1>Create User</h1>
	@if ($helperDataJson['AUisSA'])
		Type: <select id="admin" name="admin">
			<option value=0 selected="selected">Public User</option>
			<option value=1>Admin User</option>
		</select>
		SuperAdmin: <select id="sa" name="sa">
			<option value=0 selected="selected">No</option>
			<option value=1>Yes</option>
		</select>
	@endif
	Confirmed: <input type="checkbox" id="confirmed" name="confirmed" value="1" />
	Active: <input type="checkbox" id="active" name="active" value="1" /><br />
	Inactive reason: <input type="text" id="inactive_reason" name="inactive_reason" value="" /><br />
	Email: <input type="text" id="email" name="email" value="" /><br />
	Nick: <input type="text" id="nick" name="nick" value="" /><br />
	Firstname: <input type="text" id="first_name" name="first_name" value="" /><br />
	Lastname: <input type="text" id="last_name" name="last_name" value="" /><br />
@endif
<input type="submit" name="Submit" value="Save" />
</form>