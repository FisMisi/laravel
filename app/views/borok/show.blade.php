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
    @else
        <p>Nincs megjelenítendő étlap elem</p>
    @endif
@stop
