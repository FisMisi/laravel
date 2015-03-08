@extends('layouts.admin')

@section('admincontent')

  @if(count($categories))
  <p class="bg-primary">CATEGORIES</p>
   <table class="table table-striped">
    <tr>
        <th>Category Name</th>
    </tr>
    @foreach($categories as $category)
    <tr>
        <td>{{ $category->name }}</td>
    </tr>
    @endforeach
   </table>
  {{ $categories->links(); }}
  @else
  <h2><i>Nincs megjelenítendő elem</i></h2>
  @endif
@stop
