<div class="coll-sm-10" style="color: #151515">    
    
@include('helper.admin.models.tabs')
    
<a class="btn btn-primary btn-sm" href="{{ route('/administrator/modelslanguages/{id}',array('id' => 0)) }}"> New Language</a>
</div>
    <table id="languages" class="table table-striped">
		@if (!is_null($helperDataJson['languages']))
			<tr>
                            <th>Id</th>
                            <th>Sort</th>
                            <th>Name</th>
                            <th>Statusz</th>
                            <th>Actions</th>
			</tr>
			@foreach($helperDataJson['languages'] as $lang)
                            @if (isset($lang))
                                    <tr>
                                        <td>{{$lang['id']}}</td>
                                        <td>{{$lang['sort']}}</td>
                                        <td>{{$lang['name']}}</td>
                                        <td>@if($lang['active']== 1 ) Active @else Inactive @endif </td>
                                        <td>{{ Form::open(array('route' => array('/administrator/modelslanguages/update_statusz/{id}',$lang['id']), 'role' => 'form')) }}
                                            <a class="btn btn-info btn-sm" role="button" href={{route('/administrator/modelslanguages/{id}', array('id' => $lang['id']))}} >View</a>
                                            <button type="submit" name="/administrator/modelslanguages/update_statusz/{id}" class="btn btn-success btn-sm">
                                                @if($lang['active']== 1 ) Inactivated @else Activated @endif
                                            </button>
                                            {{ Form::close() }}
                                        </td>           
                                    </tr>
                            @endif
			@endforeach
		@endif
    </table>
    {{ $helperDataJson['languages']->links() }}

