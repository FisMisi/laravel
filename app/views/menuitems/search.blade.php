@extends('layouts.main')


@section('content')
    <h1>Keresés eredménye a '{{ $key }}' kulcsszóra</h1>
     @if(count($menuitems))
        <ul>
        @foreach($menuitems as $item)
            <li>
                {{ HTML::image($item->image, $item->name, array('width' => '50')) }}
                {{ link_to_route('menuitems.show', $item->name,array($item->id),array('class' => 'btn btn-link')) }}
                {{Form::open(array('route'=>array('delItem',$item->id), 'role' => 'form')) }}
                    <button type="submit" name="delCateg" class="btn btn-danger btn-sm">Törlés</button>
                {{Form::close()}}
            </li>
        @endforeach
        </ul>
    @else
        <p>Nincs keresésre eredmény</p>
    @endif
    
@stop
