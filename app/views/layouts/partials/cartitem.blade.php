<tr>
    <td> 
        {{ $item->id }}
        {{ Form::hidden('item_id'.$counter,$item->id) }}
    </td>
    <td>
        {{ HTML::image($item->image, $item->name, array('width' => '50')) }}
        {{ link_to_route('menuitems.show', $item->name,array($item->id),array('class' => 'btn btn-link')) }}
        {{ Form::hidden('item_name'.$counter,$item->name) }}
    </td>
    <td>
        {{ $item->price }}
        {{ Form::hidden('item_price'.$counter,$item->price) }}
    </td>
    <td>
        {{ $item->quantity }}
        {{ Form::hidden('item_qtt'.$counter,$item->quantity) }}
    </td>
    <td>
        {{ $item->price }}
        {{ link_to_route('menuitems.removeitem', 'Törlés a kosárból',array($item->identifier),array('class' => 'btn btn-link')) }}
    </td>
</tr>
            