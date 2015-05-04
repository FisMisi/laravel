<ul>
@if (!is_null($helperDataJson['modules']))
	@forelse($helperDataJson['modules'] as $modul)
		@if (isset($modul))
			<li @if ($modul->modul_id == $helperDataJson['actualModul']) class="actual" @endif >{{ HTML::linkRoute($modul->admin_route, $modul->modul_title) }} </li>
		@endif
	@empty
		<li>No Modules</li>
	@endforelse
@else
	<li>No Modules</li>
@endif
</ul>