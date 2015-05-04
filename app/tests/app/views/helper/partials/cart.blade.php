<h2>Kosár elemei</h2>
    
     @if($errors->has())
    <ul>
      @foreach($errors->all() as $error)
        <li>{{$error}}</li>
      @endforeach
    </ul> 
    @endif
    
    {{ Form::open(array('route' => '/postmodelregistraton/step2/payment')) }}
    <table>
        <tr>
            <th>#</th>
            <th>Product Name</th>
            <th>Price</th>
            <th>Mennyiség</th>
            <th>Subtotal</th>
        </tr>
        <?php $counter=0; ?>
        
            <tr>
                <td> 
                    1
                    {{ Form::hidden('item_id',1) }}
                </td>
                <td>
                    Lecsókolbász
                    {{ Form::hidden('item_name','lecsókolbász') }}
                </td>
                <td>
                    200Ft
                    {{ Form::hidden('item_price',200) }}
                </td>
                <td>
                    8
                    {{ Form::hidden('item_qtt',5) }}
                </td>
                
            </tr>
        
        <tr>
            <td colspan="5">
                Subtotal: 1600Ft <br />
                <span> Total: 1600 </span> <br />
                
                <input type="hidden" name="cmd" value="_xclick">
                <input type="hidden" name="business" value="teszt@ikron.hu">
                <input type="hidden" name="amount" value="1600">
                <input type="hidden" name="first_namet" value="Markó">
                <input type="hidden" name="last_name" value="Mihály">
                <input type="hidden" name="user" value="mihaly.richard.marko@gmail.com">
                
                {{ Form::submit("Fizetés") }}
            </td>
        </tr>
    </table>
    {{ Form::close() }}
    {{ link_to_route('/postmodelregistraton/step2/payout', 'Kiutalás') }}