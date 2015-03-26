@extends('layouts.admin')

@section('admincontent')
    
  @section('szuro')
    <p class="bg-primary flashmsg">szűrő</p>
        <div class="szuro">
        
            {{ Form::open(array('route' => array('admin.menuitems.postindex'),'method'=>'get','class'=>'form-inline')) }}
                {{ Form::label('availability','Elérhető?') }}
                {{ Form::select('availability', array('all' => '- All -', 'yes' => 'yes', 'no' => 'no'),null, array('selected'=>'all', 'class' => 'form-control')) }}
                {{ Form::label('type','Típus') }}
                {{ Form::select('type', array('all' => '- All -')+$categories,null, array('selected'=>'all', 'class' => 'form-control')) }}
                {{ Form::submit('GO',array('class'=>'btn btn-success')) }}
                <a class="btn btn-info" href="{{ route('/admin/menuitems/exportItems',$_GET) }}">Export Items</a>
            {{ Form::close() }}
            
        </div>
  @stop
  @if(count($menuitems))
   <p class="bg-primary flashmsg">PRODUCTS</p>
   <table class="table table-striped">
    <tr>
        <th>Name</th>
        <th>Price</th>
        <th>Type</th>
        <th>Availability</th>
    </tr>
   
    @foreach($menuitems as $menuitem)
    <tr>
        <td>{{ $menuitem->product_name }}</td>
        <td>{{ $menuitem->product_price }}</td>
        <td> {{ $menuitem->categ_name }} </td>
        <td> @if($menuitem->product_availability == 1) Yes @else No @endif  </td>
        <td>{{ link_to_route('admin.menuitems.edit', 'View',array($menuitem->menuitem_id),array('class' => 'btn btn-info btn-sm')) }}</td>
    </tr>    
    @endforeach
   </table>
  {{ $menuitems->appends(array(
              'availability' =>Input::get('availability'),
              'type' =>Input::get('type')
              ))->links(); 
  }}
  @else
  <h2><i>Nincs megjelenítendő elem</i></h2>
  @endif
@stop
