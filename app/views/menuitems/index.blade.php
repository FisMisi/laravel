@extends('layouts.main')

@section('content')
<h1>Étlap elemei</h1>

    @if(count($menuitems))
    <table class="table table-striped">
        @foreach($menuitems as $item)
            @include('layouts.partials.menuitem')
        @endforeach
        </table>
        {{ $menuitems->links() }}
    @else
        <p>Nincs megjelenítendő étlap elem</p>
    @endif
@stop