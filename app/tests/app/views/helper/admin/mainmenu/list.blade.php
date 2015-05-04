<h1>Menus</h1>
<div>
<a href="/administrator/menus/0">New</a>
</div>
<table id="menus">
	<colgroup>
		<col id="name"></col>
		<col id="title"></col>
		<col id="target"></col>
		<col id="href"></col>
		<col id="onclick"></col>
		<col id="pos"></col>
		<col id="active"></col>
		<col id="modify"></col>
	</colgroup>
	<tbody id="rows">
		@if (!is_null($helperDataJson['list']))
			<tr>
			<td>Name</td>
			<td>Title</td>
			<td>Target</td>
			<td>Href</td>
			<td>OnClick</td>
			<td>Pos</td>
			<td>Active</td>
			<td>Modify</td>
			</tr>
			@foreach($helperDataJson['list'] as $menu)
				@if (isset($menu))
					<tr>
						<td>{{$menu->name}}</td>
						<td>{{$menu->title}}</td>
						<td>{{$menu->target}}</td>
						<td>{{$menu->href}}</td>
						<td>{{$menu->onclick}}</td>
						<td>{{$menu->pos}}</td>
						<td>{{$menu->active}}</td>
						<td><a href="{{route('/administrator/menus/{id}', array('id' => $menu->mainmenu_id))}}">Modify</a></td>
					</tr>
				@endif
			@endforeach
		@endif
	</tbody>
</table>