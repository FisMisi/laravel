<tr>
    <td>
        {{ HTML::image($item->image, $item->name, array('width' => '50')) }}
    </td>
    <td>
        {{ link_to_route('menuitems.show', $item->name,array($item->id),array('class' => 'btn btn-link')) }}
    </td>    
    <td>
        {{Form::open(array('route'=>array('destroy',$item->id),'method'=>'delete')) }}
            <button type="submit" href="{{ URL::route('destroy', $item->id) }} class="btn btn-danger btn-sm">Törlés</button>
        {{Form::close()}}
    </td>
    <td>                    
        {{ Form::open(array('url'=>'menuitems/addtocart')) }}
            {{ Form::text('quantity') }}
            {{ Form::hidden('id',$item->id) }}
            <button type="submit" class="btn btn-success btn-sm">
                <span class="price"> {{ $item->price }} </span>
                ADD To Cart
            </button>
        {{ Form::close() }}
    </td>            
</tr>
            