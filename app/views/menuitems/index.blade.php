@extends('layouts.main')

@section('content')
<h1>Étlap elemei</h1>

    @if(count($menuitems))
        <ul>
        @foreach($menuitems as $item)
            <li>
                {{ HTML::image($item->image, $item->name, array('width' => '50')) }}
                {{ link_to_route('menuitems.show', $item->name,array($item->id),array('class' => 'btn btn-link')) }}
                {{Form::open(array('route'=>array('destroy',$item->id),'method'=>'delete')) }}
                    <button type="submit" href="{{ URL::route('destroy', $item->id) }} class="btn btn-danger btn-sm">Törlés</button>
                {{Form::close()}}
                
                {{ Form::open(array('url'=>'menuitems/addtocart')) }}
                    {{ Form::hidden('quantity',1) }}
                    {{ Form::hidden('quantity',1) }}
                    {{ Form::hidden('id',$item->id) }}
                    <button type="submit" class="btn btn-success btn-sm">
                        <span class="price"> {{ $item->price }} </span>
                        ADD To Cart
                    </button>
                {{ Form::close() }}
            </li>
        @endforeach
        </ul>
        {{ $menuitems->links() }}
    @else
        <p>Nincs megjelenítendő étlap elem</p>
    @endif
@stop