@extends('layouts.main')

@section('sidebar')
    @include('layouts.partials.leftSideMenuCategories')
@stop

@section('content')
    
@if(count($categoryTypes))
  <p class="bg-primary flashmsg">CATEGORY TYPES</p>
  
    @foreach($categoryTypes as $categoryType)
        <h3>{{ $categoryType->title }}</h3>
    @endforeach
  {{ $categoryTypes->links()  }}
  @else
  <h2><i>Nincs megjelenítendő elem</i></h2>
  @endif
  
@stop