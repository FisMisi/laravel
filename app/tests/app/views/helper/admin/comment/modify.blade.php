<h1>Modify Comment</h1>
<form name="aaa" action="/administrator/commentmodify" method="post">
<input type="hidden" id="comment_id" name="comment_id" value="{{$helperDataJson['comment']->comment_id}}" =>
@if (!is_null($helperDataJson['comment']))
@if ($helperDataJson['user']) 
	User: {{$helperDataJson['user']->email}}<br/>
@endif
@if ($helperDataJson['video'])
	Video: {{$helperDataJson['video']->video_name}}<br/>
@endif
Comment: <textarea id="comment" name="comment" style="width:600px;height:100px;">{{$helperDataJson['comment']->comment}}</textarea><br />
Active: <input type="checkbox" id="active" name="active" value="1" @if ($helperDataJson['comment']->active) checked="checked" @endif /><br />
@if (!is_null($helperDataJson['inactive_user']))
	 Inactive User: <strong>{{$helperDataJson['inactive_user']->email}}</strong><br/>
@endif

Inactive Reason: <textarea id="inactive_reason" name="inactive_reason" style="width:600px;height:100px;">{{$helperDataJson['comment']->inactive_reason}}</textarea><br />

@endif

<input type="submit" name="Submit" value="Save" />
</form>