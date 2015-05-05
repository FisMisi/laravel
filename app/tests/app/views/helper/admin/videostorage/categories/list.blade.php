<div class="coll-sm-10" style="color: #151515">    
    
<h1>
    <a href="{{ route('/administrator/video_storage') }}"> Videos </a> |
    <a href="{{ route('/administrator/video_storaged_categories') }}"> Video Categories </a>
</h1>
    
 <a class="btn btn-primary btn-sm" href="{{ route('/administrator/video_storaged_categories/{id}',array('id' => 0)) }}"> New Video Category</a>
</div>
    <table id="languages" class="table table-striped">
		@if (!is_null($helperDataJson['categories']))
			<tr>
                            <th>Id</th>
                            <th>Title</th>
                            <th>Name</th>
                            <th>Statusz</th>
                            <th>Actions</th>
			</tr>
			@foreach($helperDataJson['categories'] as $categ)
                            @if (isset($categ))
                                    <tr>
                                            <td>{{$categ->id}}</td>
                                            <td>{{$categ->title}}</td>
                                            <td>{{$categ->name}}</td>
                                            <td>@if($categ->active == 1 ) Active @else Inactive @endif </td>
                                            <td>{{ Form::open(array('route' => array('/administrator/video_storaged_categories/update_statusz/{id}',$categ->id), 'role' => 'form')) }}
                                                <a class="btn btn-info btn-sm" role="button" href={{route('/administrator/video_storaged_categories/{id}', array('id' => $categ->id))}} >View</a>
                                                <button type="submit" name="/administrator/video_storaged_categories/update_statusz/{id}" class="btn btn-success btn-sm">
                                                    @if($categ->active == 1 ) Inactivated @else Activated @endif
                                                </button>
                                                {{ Form::close() }}
                                            </td>           
                                    </tr>
                            @endif
			@endforeach
		@endif
    </table>
    {{ $helperDataJson['categories']->links() }}

