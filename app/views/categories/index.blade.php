@extends('layouts.main')

@section('content')
<h1>Kategóriák</h1>

    @if(count($categories))
        <ul>
        @foreach($categories as $categ)
            <li> 
                {{ $categ->name }}-
                {{Form::open(array('route'=>array('delCateg',$categ->id), 'role' => 'form')) }}
                    <button type="submit" name="delCateg" class="btn btn-danger btn-sm">Törlés</button>
                {{Form::close()}}
            </li>
        @endforeach
        </ul>
    @else
        <p>Nincs megjelenítendő kategória</p>
    @endif
@stop
