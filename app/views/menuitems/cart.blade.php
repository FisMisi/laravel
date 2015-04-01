@extends('layouts.main')

@section('content')
<h1>Kosár elemei</h1>

    @if(count($products))
    <form action="https://www.paypal.com/cgi-bin/webscr" method="POST">
    <table class="table-striped">
        <tr>
            <th>#</th>
            <th>Product Name</th>
            <th>Price</th>
            <th>Mennyiség</th>
            <th>Subtotal</th>
        </tr>
        @foreach($products as $item)
            <tr>
                <td> {{ $item->id }} </td>
                <td>
                    {{ HTML::image($item->image, $item->name, array('width' => '50')) }}
                    {{ link_to_route('menuitems.show', $item->name,array($item->id),array('class' => 'btn btn-link')) }}
                </td>
                <td>
                    {{ $item->price }}
                </td>
                <td>
                    {{ $item->quantity }}
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
                <input type="hidden" name="business" value="office@shop.com">
                <input type="hidden" name="item_name" value="eCommerce Store Purchase">
                <input type="hidden" name="amount" value="{{ Cart::total() }}">
                <input type="hidden" name="first_namet" value="Markó">
                <input type="hidden" name="last_name" value="Mihály">
                <input type="hidden" name="amount" value="mihaly.richard.marko@gmail.com">
                
                {{ HTML::link('/','Continoue Shoping', array('class'=>'btn btn-link')) }}
                <input type="submit" value="Kifizetés PayPal">
            </td>
        </tr>
    </table>
    </form>
      
    @else
        <p>Nincs megjelenítendő elem</p>
    @endif
@stop