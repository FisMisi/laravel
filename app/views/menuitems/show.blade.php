@extends('layouts.main')

@section('content')
<h1>Étlap elemei</h1>

    @if(count($item))
        <ul>
            <li>
                {{ HTML::image($item->image, $item->name, array('width' => '50')) }}
            </li>
            <li>
                Név: {{$item->name }}
            </li>
            <li>
                Ár: {{$item->price }}Ft
            </li>
            <li>
                típus: {{$item->type }}
            </li>
            <li>
                Státusz: {{ Helper::checkAvailability($item->availability) }}
            </li>
        </ul>
        {{ Form::open(array('url'=>'menuitems/addtocart')) }}
                    {{ Form::label('quantity', 'Mennyiség') }}
                    {{ Form::text('quantity',1,array('maxlength' => 2)) }}
                    
                    {{ Form::hidden('id',$item->id) }}
                    <button type="submit" class="btn btn-success btn-sm">
                        <span class="price"> 500 Ft </span>
                        ADD To Cart
                    </button>
                {{ Form::close() }}
    @else
        <p>Nincs megjelenítendő étlap elem</p>
    @endif
@stop
