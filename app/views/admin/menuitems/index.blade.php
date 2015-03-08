@extends('layouts.admin')

@section('admincontent')

  @if(count($menuitems))
   <p class="bg-primary">PRODUCTS</p>
   <table class="table table-striped">
    <tr>
        <th>Name</th>
        <th>Price</th>
        <th>Type</th>
        <th>Availability</th>
    </tr>
   
    @foreach($menuitems as $menuitem)
    <tr>
        <td>{{ $menuitem->product_name }}</td>
        <td>{{ $menuitem->product_price }}</td>
        <td> {{ $menuitem->categ_name }} </td>
        <td> @if($menuitem->product_availability == 1) Yes @else No @endif  </td>
    </tr>    
    @endforeach
   </table>
  {{ $menuitems->links(); }}
  @else
  <h2><i>Nincs megjelenítendő elem</i></h2>
  @endif
@stop
