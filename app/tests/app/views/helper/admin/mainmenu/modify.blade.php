<form method="POST" action="/administrator/savemainmenu">
@if ($helperDataJson['new'] == 1)
<input type="hidden" name="mainmenu_id" id="mainmenu_id" value='0' />
<h1>Create New Menu</h1>
<div>
	Name: <input type="text" name="name" id="name" />
</div>

<div>
	Title: <input type="text" name="title" id="title" />
</div>

<div>
	Target: <input type="text" name="target" id="target" />
</div>

<div>
	Href: <input type="text" name="href" id="href" />
</div>

<div>
	onClick: <input type="text" name="onclick" id="onclick" />
</div>

<div>
	Pos: <input type="text" name="pos" id="pos" />
</div>

<div>
	Active: <input type="checkbox" name="active" id="active" value="1" />
</div>
@else
<input type="hidden" name="mainmenu_id" id="mainmenu_id" value="{{$helperDataJson['menu']->mainmenu_id}}" />
<h1>Modify Menu</h1>
<div>
	Name: <input type="text" name="name" id="name" value="{{$helperDataJson['menu']->name}}" />
</div>

<div>
	Title: <input type="text" name="title" id="title" value="{{$helperDataJson['menu']->title}}" />
</div>

<div>
	Target: <input type="text" name="target" id="target" value="{{$helperDataJson['menu']->target}}" />
</div>

<div>
	Href: <input type="text" name="href" id="href" value="{{$helperDataJson['menu']->href}}" />
</div>

<div>
	onClick: <input type="text" name="onclick" id="onclick" value="{{$helperDataJson['menu']->onclick}}" />
</div>

<div>
	Pos: <input type="text" name="pos" id="pos" value="{{$helperDataJson['menu']->pos}}" />
</div>

<div>
	Active: <input type="checkbox" name="active" id="active" value="1" @if($helperDataJson['menu']->active) checked @endif />
</div>

@endif
<input type="submit" value="save" />
</for>
