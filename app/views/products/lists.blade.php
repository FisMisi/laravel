@extends('layouts.main')

@section('sidebar')
    @include('layouts.partials.leftSideMenuCategories')
@stop

@section('content')
  
@if(count($users))
  <p class="bg-primary flashmsg">Result</p>
  
    @foreach($users as $user)
    
       <div>
         <hr />
        <h3>{{ $user['userName'] }}</h3>
        {{ HTML::image($user['image'],$user['image'],array('width' => '50')) }}
       
        @foreach(Category::getCategoriesByUserId($user['userId']) as $category)
        
            {{ $category->name }}
        
        @endforeach
        
       </div> 
    @endforeach
  
  @else
  <h2><i>Nincs megjelenítendő elem</i></h2>
  @endif
  
@stop