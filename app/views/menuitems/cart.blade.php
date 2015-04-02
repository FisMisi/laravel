@extends('layouts.main')

@section('content')
<h1>Kosár elemei</h1>

    @if(count($products))
    {{ Form::open(array('route' => 'payment', 'class' => 'form-horizontal')) }}
    <table class="table-striped">
        <tr>
            <th>#</th>
            <th>Product Name</th>
            <th>Price</th>
            <th>Mennyiség</th>
            <th>Subtotal</th>
        </tr>
        <?php $counter=0; ?>
        @foreach($products as $item)
            <tr>
                <td> 
                    {{ $item->id }}
                    {{ Form::hidden('item_id',$item->id) }}
                </td>
                <td>
                    {{ HTML::image($item->image, $item->name, array('width' => '50')) }}
                    {{ link_to_route('menuitems.show', $item->name,array($item->id),array('class' => 'btn btn-link')) }}
                    {{ Form::hidden('item_name',$item->name) }}
                </td>
                <td>
                    {{ $item->price }}
                    {{ Form::hidden('item_price',$item->price) }}
                </td>
                <td>
                    {{ $item->quantity }}
                    {{ Form::hidden('item_qtt',$item->quantity) }}
                </td>
                <td>
                    {{ $item->price }}
                    <a href="/menuitems/removeitem/{{ $item->identifier }}">Törlés a kosárból</a>
                </td>
               
            </tr>
        @endforeach
        
        <tr>
            <td colspan="5">
                Subtotal: {{ Cart::total() }} <br />
                <span> Total: {{ Cart::total() }} </span> <br />
                
                <input type="hidden" name="cmd" value="_xclick">
                <input type="hidden" name="business" value="teszt@ikron.hu">
                <input type="hidden" name="amount" value="{{ Cart::total() }}">
                <input type="hidden" name="first_namet" value="Markó">
                <input type="hidden" name="last_name" value="Mihály">
                <input type="hidden" name="user" value="mihaly.richard.marko@gmail.com">
                
                {{ HTML::link('/','Continoue Shoping', array('class'=>'btn btn-link')) }}
                <input type="submit" value="Kifizetés PayPal">
            </td>
        </tr>
    </table>
    {{ Form::close() }}
      
    @else
        <p>Nincs megjelenítendő elem</p>
    @endif
@stop
