@extends('layouts.main')

@section('content')
<h1>Étlap elemei</h1>

    @if(count($menuitems))
        <ul>
        @foreach($menuitems as $item)
            <li>
                {{ HTML::image($item->image, $item->name, array('width' => '50')) }}
                {{$item->name }}-
                {{Form::open(array('route'=>array('delItem',$item->id), 'role' => 'form')) }}
                    <button type="submit" name="delCateg" class="btn btn-danger btn-sm">Törlés</button>
                {{Form::close()}}
            </li>
        @endforeach
        </ul>
    @else
        <p>Nincs megjelenítendő étlap elem</p>
    @endif
@stop
