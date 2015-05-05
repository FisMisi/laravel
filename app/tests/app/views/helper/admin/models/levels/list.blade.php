<div class="coll-sm-10" style="color: #151515">    
    
@include('helper.admin.models.tabs')
    
 <a class="btn btn-primary btn-sm" href="{{ route('/administrator/model_levels/{id}',array('id' => 0)) }}"> New Level</a>
</div>
    @if (count($helperDataJson['levels']))
    <table class="table table-striped">
		
        <tr>
            <th>#Id</th>
            <th>Title</th>
            <th>Created</th>
            <th>Updated</th>
        </tr>
        @foreach($helperDataJson['levels'] as $level)
            @if (isset($level))
                    <tr>
                        <td>{{$level['id']}}</td>
                        <td>{{$level['title']}}</td>
                        <td>{{$level['created_at']}}</td>
                        <td>{{$level['updated_at']}}</td>

                        <td>
                            <a class="btn btn-info btn-sm" role="button" href={{route('/administrator/model_levels/{id}', array('id' => $level['id']))}} >Edit</a>
                        </td>           
                    </tr>
            @endif
        @endforeach
    </table>
        {{ $helperDataJson['levels']->links() }}
    @else
    <h3>Nincs találat</h3>
    @endif
    

