@extends('layouts.main')

@section('content')
<h1>Kosár elemei</h1>

    @if(count($products))
    {{ Form::open(array('route' => 'payment', 'class' => 'form-horizontal')) }}
    <table class="table table-striped">
        <tr>
            <th>#</th>
            <th>Product Name</th>
            <th>Price</th>
            <th>Mennyiség</th>
            <th>Subtotal</th>
        </tr>
        <?php $counter=0; ?>
        @foreach($products as $item)
            @include('layouts.partials.cartitem',array(
                                                'item'=>$item,
                                                'counter'=>$counter
                                                ))
            <?php 
                $counter++;
            ?>
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
                
                {{ HTML::link('/menuitems','Continoue Shoping', array('class'=>'btn btn-link')) }}
                <input type="submit" value="Kifizetés PayPal">
            </td>
        </tr>
    </table>
    {{ Form::close() }}
      
    @else
        <p>Nincs megjelenítendő elem</p>
    @endif
@stop
